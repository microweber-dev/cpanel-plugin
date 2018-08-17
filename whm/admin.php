<?php
require_once('/usr/local/cpanel/php/WHM.php');
require_once(__DIR__ . '/../lib/MicroweberStorage.php');
require_once(__DIR__ . '/../lib/MicroweberView.php');
require_once(__DIR__ . '/../lib/MicroweberVersionsManager.php');
require_once(__DIR__ . '/../lib/MicroweberAdminController.php');


$controller = new MicroweberAdminController();
$versions = new MicroweberVersionsManager();
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


$current_version = $versions->getCurrentVersion();
$latest_version = $versions->getLatestVersion();


//$autoInstall = isset($storedData->auto_install) && $storedData->auto_install == '1';
//$install_type = isset($storedData->install_type) && $storedData->install_type == 'symlinked';
//$user_key = isset($storedData->key) ? $storedData->key : '';


$domains = $controller->get_installations_across_server();


WHM::header('Microweber Settings', 0, 0);
?>
<link rel="stylesheet" type="text/css" href="./microweber/index.css">

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

<h1>Settings</h1>
<?php

$view = new MicroweberView(__DIR__ . '/../views/settings.php');
$view->assign('settings', $settings);
$view->display();


?>
<hr>
<h1>Download</h1>
<?php

$view = new MicroweberView(__DIR__ . '/../views/download.php');
$view->assign('key', $user_key);
$view->assign('key_data', $keyData);
$view->assign('current_version', $current_version);
$view->assign('latest_version', $latest_version);
$view->display();


?>


<hr>


<h1>Installations</h1>
<?php

$view = new MicroweberView(__DIR__ . '/../views/domains.php');
$view->assign('domains', $domains);
$view->display();


?>
<?php
WHM::footer();
?>
