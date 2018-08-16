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
if (isset($_POST['key'])) {
    $storage->save($_POST);
}

if (isset($_POST['download_cms'])) {
    $versions->download();
}
$current_version = $versions->getCurrentVersion();
$latest_version = $versions->getLatestVersion();
 
$settings = $storage->read();


//$autoInstall = isset($storedData->auto_install) && $storedData->auto_install == '1';
//$install_type = isset($storedData->install_type) && $storedData->install_type == 'symlinked';
//$whiteLabelKey = isset($storedData->key) ? $storedData->key : '';
$whiteLabelKey = isset($settings['key']) ? $settings['key'] : '';
// Check white label key
if ($whiteLabelKey) {
    $keyData = $controller->getLicenseData($whiteLabelKey);

//    $relType = 'modules/white_label';
//    $check_url = "https://update.microweberapi.com/?api_function=validate_licenses&local_key=$whiteLabelKey&rel_type=$relType";
//    $data = file_get_contents($check_url);
//    $data = @json_decode($data, true);
//    $keyData = $data[$relType];
}


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
$view->assign('key', $whiteLabelKey);
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
