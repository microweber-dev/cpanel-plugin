<?php

if (!isset($settings)) {
    return;
}
$white_label_key = isset($settings['key']) ? $settings['key'] : '';
$auto_install = isset($settings['auto_install']) ? $settings['auto_install'] : '';
//$install_type = isset($settings['install_type']) && $settings['install_type'] == 'symlinked';
$install_type = isset($settings['install_type']) ? $settings['install_type'] : '' ;
$db_driver = isset($settings['db_driver'])  ? $settings['db_driver'] : '';


?>

<form method="POST">
    <input type="hidden" name="save_settings" value="1" >
    <div>
        <div>
            <h2>Automatic Installation</h2>

            <div>
                <label>
                    <input type="radio" name="auto_install" value="1" <?php echo $auto_install == '1' ? 'checked' : ''; ?>>
                    Automatically install Microweber on new domains and subdomains creation.
                </label>

                <br>

                <label>
                    <input type="radio" name="auto_install" value="0" <?php echo $auto_install == '0' ? 'checked' : ''; ?>>
                    Manually install Microweber from cPanel.
                </label>
                <br>
                <label>
                    <input type="radio"
                           name="auto_install" <?php echo $auto_install == 'disabled' ? 'checked' : ''; ?> value="disabled">
                    Disabled
                </label>
                <br>

            </div>


            <h2>Installation Type</h2>

            <div>
                <label>
                    <input type="radio" name="install_type" value="normal" <?php if ($install_type != 'symlinked') { print 'checked' ;}  ?>>
                    Default
                </label>
                <br>
                <label>

                    <input type="radio" name="install_type"
                           value="symlinked" <?php if ($install_type == 'symlinked') { print 'checked' ;}  ?>  >
                    Sym-linked (saves a big amount of disk space)
                </label>
                <br>
            </div>


            <h2>Database Driver</h2>

            <div>
                <label>
                    <select name="db_driver" class="form-control">


                        <option <?php if ($db_driver == 'mysql') { print 'selected' ;}  ?> value="mysql" >MySQL</option>
                        <option <?php if ($db_driver == 'sqlite') { print 'selected' ;}  ?> value="sqlite">SQLite</option>
                    </select>
                </label>
            </div>
        </div>


        <div>
            <input type="submit" value="Save" class="btn btn-primary">
        </div>
    </div>
</form>