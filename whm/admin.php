<?php
require_once('/usr/local/cpanel/php/WHM.php');
require_once(__DIR__ . '/../lib/MicroweberStorage.php');
require_once(__DIR__ . '/../lib/MicroweberVersionsManager.php');

$versions = new MicroweberVersionsManager();
$storage = new MicroweberStorage();
$keyData = array();
if ($_POST) {
    $storage->save($_POST);
}
$storedData = $storage->read();
$autoInstall = isset($storedData->auto_install) && $storedData->auto_install == '1';
$install_type = isset($storedData->install_type) && $storedData->install_type == 'symlinked';
$whiteLabelKey = isset($storedData->key) ? $storedData->key : '';

// Check white label key
if($whiteLabelKey) {
    $relType = 'modules/white_label';
    $check_url = "https://update.microweberapi.com/?api_function=validate_licenses&local_key=$whiteLabelKey&rel_type=$relType";
    $data = file_get_contents($check_url);
    $data = @json_decode($data, true);
    $keyData = $data[$relType];
}

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

<form method="POST">
    <div>
        <div>
            <h2>Automatic Installation</h2>
            <div>
                <label>
                    <input type="radio" name="auto_install" value="1" <?php echo $autoInstall ? 'checked' : ''; ?>>
                    Automatically install Microweber on new domains and subdomains creation.
                </label>

                <br>
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



            </div>


            <h2>Installation Type</h2>

            <div>
                <label>
                    <input type="radio" name="install_type" value="" <?php echo !$install_type ? 'checked' : ''; ?>>
                    Default
                </label>
                <br>
                <label>
                    <input type="radio" name="install_type" value="symlinked" <?php echo $install_type ? 'checked' : ''; ?>>
                Sym-linked (saves a big amount of disk space)
                </label>
                <br>
            </div>


            <h2>Database Driver</h2>

            <div>
                <label>
                    <select name="db_driver" class="form-control">
                        <option value="mysql">MySQL</option>
                        <option value="sqlite">SQLite</option>
                    </select>
                </label>
            </div>
        </div>

        <div>
            <h2>Download Microweber</h2>

            <div>
                <button class="btn">Download Microweber CMS Latest Version</button>
            </div>
        </div>
            
        <div>
            <h2>White Label Key</h2>

            <div>
                <label>
                    Place Your Microweber White Label Key:
                    <input type="text" name="key" class="form-control" value="<?php echo $whiteLabelKey; ?>">
                </label>
            </div>

            <?php if($keyData): ?>
            <div class="callout callout-cpanel">
                <?php if($keyData['status'] == 'active'): ?>
                    <p><b><?php echo $keyData['rel_name']; ?></b></p>
                    <p>
                        <b><?php echo $keyData['registered_name']; ?></b>,
                        <?php echo $keyData['company_name']; ?>
                    </p>
                    <p>
                        License active from
                        <b><?php echo date('d M Y', strtotime($keyData['reg_on'])); ?></b>
                        to
                        <b><?php echo date('d M Y', strtotime($keyData['due_on'])); ?></b>
                    </p>
                    <p>Billing cycle: <?php echo $keyData['billing_cycle']; ?></p>
                    <button class="btn">Download Userfiles</button>
                <?php else: ?>
                    <b>
                        Your White Label key is invalid.
                        <a href="https://microweber.com/marketplace#modules">Get your license here.</a>
                    </b>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <br><br>

        <div>
            <input type="submit" value="Save" class="btn btn-primary">
        </div>
    </div>
</form>

<?php
WHM::footer();
?>
