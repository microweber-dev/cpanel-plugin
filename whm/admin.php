<?php
require_once('/usr/local/cpanel/php/WHM.php');
require_once(__DIR__ . '/../lib/MicroweberStorage.php');
require_once(__DIR__ . '/../lib/MicroweberView.php');
require_once(__DIR__ . '/../lib/MicroweberVersionsManager.php');
require_once(__DIR__ . '/../lib/MicroweberAdminController.php');
require_once(__DIR__ . '/../lib/MicroweberInstallCommand.php');


$controller = new MicroweberAdminController();
$versions = new MicroweberVersionsManager();
$install_command = new MicroweberInstallCommand();
$storage = new MicroweberStorage();
$keyData = array();
$settings = $storage->read();

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
if (isset($_POST['download_userfiles'])) {
    $versions->downloadExtraContent($user_key);
}


if (isset($_POST["_action"])) {
    $_action = $_POST["_action"];
    if ($_action == "_do_update") {

        if (isset($_POST["domain"])) {
            $domain_update_data = htmlspecialchars_decode($_POST["domain"]);
            $domain_update_data = @json_decode($domain_update_data, true);

            $update_opts = array();
            $update_opts['public_html_folder'] = $domain_update_data["documentroot"];
            $install_command->update($update_opts);

        }
    }
}


$current_version = $versions->getCurrentVersion();
$latest_version = $versions->getLatestVersion();
$latest_dl_date = $versions->getCurrentVersionLastDownloadDateTime();


//$autoInstall = isset($storedData->auto_install) && $storedData->auto_install == '1';
//$install_type = isset($storedData->install_type) && $storedData->install_type == 'symlinked';
//$user_key = isset($storedData->key) ? $storedData->key : '';


$domains = $controller->get_installations_across_server();


WHM::header('Microweber Settings', 0, 0);
?>

<style>
    label {
        font-weight: normal !important;
    }

    h2 {
        font-weight: bold !important;
    }

    .btn {
        background-color: #0086db !important;
        color: #fff !important;
    }
</style>

<hr>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title">Settings</h2>
    </div>
    <div class="panel-body">

        <?php

        $view = new MicroweberView(__DIR__ . '/../views/settings.php');
        $view->assign('settings', $settings);
        $view->display();


        ?>


    </div>
</div>


<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title">Download</h2>
    </div>
    <div class="panel-body">


        <?php

        $view = new MicroweberView(__DIR__ . '/../views/download.php');
        $view->assign('key', $user_key);
        $view->assign('key_data', $keyData);
        $view->assign('current_version', $current_version);
        $view->assign('latest_version', $latest_version);
        $view->assign('last_download_date', $latest_dl_date);
        $view->display();


        ?>


    </div>
</div>


<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title">Installations</h2>
    </div>
    <div class="panel-body">


        <?php

        $view = new MicroweberView(__DIR__ . '/../views/domains.php');
        $view->assign('domains', $domains);
        $view->assign('admin_view', true);

        $view->display();


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
