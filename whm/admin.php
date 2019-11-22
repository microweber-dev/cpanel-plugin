<?php


require_once('/usr/local/cpanel/php/WHM.php');

require_once(__DIR__ . '/../lib/MicroweberStorage.php');
require_once(__DIR__ . '/../lib/MicroweberView.php');
require_once(__DIR__ . '/../lib/MicroweberVersionsManager.php');
require_once(__DIR__ . '/../lib/MicroweberAdminController.php');
require_once(__DIR__ . '/../lib/MicroweberInstallCommand.php');
require_once(__DIR__ . '/../lib/MicroweberWhmcsConnector.php');

$controller = new MicroweberAdminController();
$versions = new MicroweberVersionsManager();
$install_command = new MicroweberInstallCommand();

$whmcs_connector = new MicroweberWhmcsConnector($install_command);

$extras_folder = $install_command->getExtrasDir();

$storage = new MicroweberStorage();
$keyData = array();
$settings = $storage->read();


if (isset($_GET['ajax_view'])) {

    $ajax_view = $_GET['ajax_view'];


    switch ($ajax_view) {
        case 'domains':
            $domains = $controller->get_installations_across_server();

            $view = new MicroweberView(__DIR__ . '/../views/domains.php');
            $view->assign('domains', $domains);
            $view->assign('admin_view', true);
            $view->display();

            break;
    }


    return;
}


$storage_whmcs_connector_settings = $whmcs_connector->getSettings();


// Check white label key


if (isset($_POST['key']) or isset($_POST['save_settings'])) {
    $storage->save($_POST);
    $settings = $storage->read();
}

$user_key = isset($settings['key']) ? $settings['key'] : '';

if ($user_key) {
    $keyData = $controller->getLicenseData($user_key);
}


if (isset($_POST['download_cms'])) {
    $versions->download();
}

if (isset($_POST['update_plugin'])) {
    $versions->downloadPlugin();
}
if (isset($_POST['download_userfiles'])) {
    $versions->downloadExtraContent($user_key);
}


if (isset($_POST["_action"])) {
    $_action = $_POST["_action"];
    unset($_POST["_action"]);

    if ($_action == "_do_update") {

        if (isset($_POST["domain"])) {
            $domain_update_data = htmlspecialchars_decode($_POST["domain"]);
            $domain_update_data = @json_decode($domain_update_data, true);

            $update_opts = array();
            $update_opts['public_html_folder'] = $domain_update_data["documentroot"];
            $install_command->update($update_opts);

        }
    }

    if ($_action == "_save_branding") {
        $settings = $storage->read();
        $settings['branding'] = $_POST;
        $storage->save($settings);
    }


    if ($_action == "_save_whmcs_connector_settings") {
        $whmcs_connector->saveSettings($_POST);
        $storage_whmcs_connector_settings = $whmcs_connector->getSettings();

    }
}
$branding = false;
if (isset($settings['branding'])) {
    $branding = $settings['branding'];
}


$current_version = $versions->getCurrentVersion();
$latest_version = $versions->getLatestVersion();
$latest_plugin_version = $versions->getLatestPluginVersion();
$current_plugin_version = $versions->getCurrentPluginVersion();
$current_templates = $versions->templatesList();
$supported_langs = $versions->getSupportedLanguages();

$latest_dl_date = $versions->getCurrentVersionLastDownloadDateTime();


//$autoInstall = isset($storedData->auto_install) && $storedData->auto_install == '1';
//$install_type = isset($storedData->install_type) && $storedData->install_type == 'symlinked';
//$user_key = isset($storedData->key) ? $storedData->key : '';


WHM::header('Microweber Settings', 1, 1);
?>
<?php
$view = new MicroweberView(__DIR__ . '/../views/header.php');

$view->display();


?>

    <div class="alert alert-info js-cms-plugin" style="display: none;">
        <div class="content">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        Your Microweber CMS version is out of date. Update it!
                    </div>
                    <div class="col-md-6" style="text-align: right;">
                        <button name="download_cms" value="download_cms" class="btn btn-primary btn-xs">UPDATE
                            MICROWEBER
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-info js-update-plugin" style="display: none;">
        <div class="content">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        Your cPanel Microweber Plugin is out of date. Update it!
                    </div>
                    <div class="col-md-6" style="text-align: right;">
                        <button name="update_plugin" value="update_plugin" class="btn btn-primary btn-xs">UPDATE
                            PLUGIN
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <hr>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Version</h2>
        </div>
        <div class="panel-body">
            <?php
            $view = new MicroweberView(__DIR__ . '/../views/download.php');
            $view->assign('key', $user_key);
            $view->assign('key_data', $keyData);
            $view->assign('current_version', $current_version);
            $view->assign('latest_version', $latest_version);
            $view->assign('last_download_date', $latest_dl_date);
            $view->assign('latest_plugin_version', $latest_plugin_version);
            $view->assign('current_plugin_version', $current_plugin_version);
            $view->assign('current_templates', $current_templates);
             $view->display();
            ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Settings</h2>
        </div>
        <div class="panel-body">
            <?php
            $view = new MicroweberView(__DIR__ . '/../views/settings.php');
            $view->assign('settings', $settings);
            $view->assign('supported_langs', $supported_langs);
            $view->display();
            ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">White label</h2>
        </div>
        <div class="panel-body">
            <?php
            $view = new MicroweberView(__DIR__ . '/../views/white_label.php');
            $view->assign('key', $user_key);
            $view->assign('key_data', $keyData);
            $view->assign('current_version', $current_version);
            $view->assign('latest_version', $latest_version);
            $view->assign('last_download_date', $latest_dl_date);
            $view->assign('latest_plugin_version', $latest_plugin_version);
            $view->assign('current_plugin_version', $current_plugin_version);
            $view->assign('settings', $settings);
            $view->assign('branding', $branding);
            $view->display();
            ?>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">WHMCS Connection</h2>
        </div>
        <div class="panel-body">
            <?php
            $view = new MicroweberView(__DIR__ . '/../views/whmcs_connector.php');
            $view->assign('whmcs_connector_settings', $storage_whmcs_connector_settings);
            $view->display();
            ?>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Installations</h2>
        </div>
        <div class="panel-body">
            <script>

                $(document).ready(function () {
                    $("#domains-ajax-result").load("?ajax_view=domains", function () {
                        //alert("Load was performed.");
                    });
                });


            </script>
            <div id="domains-ajax-result">
                Loading...
            </div>
            <?php
            //        $view = new MicroweberView(__DIR__ . '/../views/domains.php');
            //        $view->assign('domains', $domains);
            //        $view->assign('admin_view', true);
            //        $view->display();
            ?>
        </div>
    </div>


<?php
$view = new MicroweberView(__DIR__ . '/../views/footer.php');
$view->display();
?>

<?php
WHM::footer();
?>