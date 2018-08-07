<?php
require_once('/usr/local/cpanel/php/WHM.php');
require_once(__DIR__ . '/../lib/MicroweberStorage.php');

$storage = new MicroweberStorage();
if ($_POST) {
    $storage->save($_POST);
}
$storedData = $storage->read();
$autoInstall = isset($storedData->auto_install) && $storedData->auto_install == '1';
$install_type = isset($storedData->install_type) && $storedData->install_type == 'symlinked';

WHM::header('Microweber', 0, 0);
?>


<h1>Settings</h1>
<form method="POST">
    <h2>Installations settings</h2>
    <div>
        <label>
            <input type="radio" name="auto_install" <?php echo !isset($storedData->auto_install) ? 'checked' : ''; ?>>
            Default
        </label>
        <br>
        <label>
            <input type="radio" name="auto_install" value="0" <?php echo !$autoInstall ? 'checked' : ''; ?>>
            Manually install Microweber from cPanel.
        </label>
        <br>
        <label>
            <input type="radio" name="auto_install" value="1" <?php echo $autoInstall ? 'checked' : ''; ?>>
            Automatically install Microweber on new domains and subdomains creation.
        </label>
    </div>


    <h2>Installation type</h2>

    <div>
        <label>
            <input type="radio" name="install_type" value="" <?php echo !$install_type ? 'checked' : ''; ?>>
            Default
        </label>
        <br>
        <label>
            <input type="radio" name="install_type" value="symlinked" <?php echo $install_type ? 'checked' : ''; ?>>
           Symlinked
        </label>
        <br>

    </div>






    <div>
        <input type="submit" value="Save">
    </div>
</form>
<hr>
<h1>Versions</h1>

<?php
WHM::footer();
?>
