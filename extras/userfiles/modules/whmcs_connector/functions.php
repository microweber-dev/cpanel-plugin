<?php


if (!defined('MW_WHMCS_CONNECTOR_SETTINGS_FILE')) {
    define('MW_WHMCS_CONNECTOR_SETTINGS_FILE', __DIR__ . DIRECTORY_SEPARATOR . 'settings.json');
    define('MW_WHMCS_CONNECTOR_SETTINGS_FILE_LOCAL', storage_path() . DIRECTORY_SEPARATOR . 'whmcs_connector.json');
}


event_bind('mw.admin.dashboard.main', function ($params = false) {


    $is_data = mw()->user_manager->session_get('mw_hosting_data');
    if ($is_data and is_array($is_data)) {
        //print '<module type="users/mw_login/hosting" />';
    }

});


event_bind('on_load', function ($params = false) {

});


event_bind('mw.user.before_login', function ($params = false) {
    return mw_whmcs_remote_user_login($params);
});

event_bind('mw.ui.admin.login.form.after', function ($params = false) {

    //$btn_url = 'https://members.microweber.com/go_to_product.php?domain=' . site_url();

    $btn_url = mw_whmcs_remote_get_connector_url().'index.php?m=microweber_addon&function=go_to_product&domain='. site_url();





    $username_path = explode('public_html', mw_root_path());
    if (isset($username_path[0])) {
        $username_path = explode('/', $username_path[0]);
        if ($username_path) {
            $username_path = array_filter($username_path);
            if ($username_path) {
                $username_path = array_pop($username_path);
                if ($username_path) {
                    $btn_url = mw_whmcs_remote_get_connector_url().   'index.php?m=microweber_addon&function=go_to_product&username2=' . $username_path . '&return_domain=' . site_url();
                }
            }
        }
    }

    print "<center>";
    print "<h4>OR</h4>";

    print "<h4>Use Microweber.com Account</h4>";
    print "<br>";

    print '<a class="mw-ui-btn  mw-ui-btn-info mw-ui-btn-big" href="' . $btn_url . '"><span class="mw-icon-login"></span>Login with your account</a>';
    print "</center>";


    return;
});


function mw_whmcs_remote_get_connector_url()
{
    $file = false;
    if (is_file(MW_WHMCS_CONNECTOR_SETTINGS_FILE_LOCAL)) {
        $file = MW_WHMCS_CONNECTOR_SETTINGS_FILE_LOCAL;
    } elseif (is_file(MW_WHMCS_CONNECTOR_SETTINGS_FILE)) {
        $file = MW_WHMCS_CONNECTOR_SETTINGS_FILE;
    }

    if (is_file($file)) {
        $settings = json_decode(file_get_contents($file), true);
        if ($settings and isset($settings['whmcs_url'])) {
            return $settings['whmcs_url'];
        }

        if ($settings and isset($settings['url'])) {
            return $settings['url'];
        }
    }

}


function mw_whmcs_remote_user_login($params = false)
{


    if ($params == false) {
        return;
    }
    if (!is_array($params)) {
        $params = parse_params($params);
    }
    $postfields = array();
    $postfields['action'] = 'validatelogin';
    if (isset($params['email'])) {
        $params['username'] = $params['email'];
    }

    if (!isset($params['username'])) {
        return false;
    }
    if (!isset($params['password'])) {
        return false;
    }
    $postfields = $params;
    $postfields["email"] = $params['username'];
    $postfields["password2"] = $params['password'];
    $postfields["domain"] = site_url();
 //dd($postfields);

    $result = mw_whmcs_remote_user_login_exec($postfields);
   // dd($result);
    if (isset($result['hosting_data'])) {
        mw()->user_manager->session_set('mw_hosting_data', $result['hosting_data']);
    }


    if (isset($result['result']) and $result['result'] == 'success' and isset($result['userid'])) {

        cache_clear('users');

        $check_if_exists = get_users('no_cache=1&one=1&email=' . $params['username']);
        if (!$check_if_exists) {
            $check_if_exists = get_users('no_cache=1&one=1&username=' . $params['username']);

        }
        if (!$check_if_exists) {
            $check_if_exists = get_users('no_cache=1&one=1&oauth_provider=mw_login&oauth_uid=' . intval($result['userid']));
        }

        $upd = array();
        if ($check_if_exists == false) {
            // $upd['id'] = 0;
        } else {
            $upd['id'] = $check_if_exists['id'];


        }
        if (is_array($check_if_exists) and isset($check_if_exists['is_active'])) {
            $upd['is_active'] = $check_if_exists['is_active'];
        } else {
            $upd['is_active'] = 1;
        }


        $upd['email'] = $params['username'];
        $upd['password'] = $params['password'];
        $upd['is_admin'] = 1;


        $upd['oauth_uid'] = $result['userid'];
        $upd['oauth_provider'] = 'mw_login';
        if (!defined('MW_FORCE_USER_SAVE')) {
            define('MW_FORCE_USER_SAVE',1);
        }

        $s = save_user($upd);


      //  dd($s);

        if (intval($s) > 0) {


            $login = mw()->user_manager->make_logged($s);
        //    dd($login);

            if (isset($login['success']) or isset($login['error'])) {
                return $login;
            }
        }

    } else if (isset($result['error'])) {
        return $result;
    }


}

function mw_whmcs_remote_user_login_exec($params)
{
    if (!is_array($params)) {
        $params = parse_params($params);
    }


    $cache_time = false;
    if (isset($params['cache'])) {
        $cache_time = intval($params['cache']);
    }

    $url = mw_whmcs_remote_get_connector_url().'index.php?m=microweber_addon&function=login_to_my_website';







    $postfields = $params;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);


    curl_close($ch);



//    var_dump($data);
//    exit;

//    print_r($url);
////    print_r($postfields);
//    print_r($data);
//    exit;

    //var_dump($data);

    $data = @json_decode($data, true);
//    var_dump($data);
//    exit;

    return $data;

}
 

