<?php

if (!isset($settings) or !$settings) {
    $settings = array();
}
$white_label_key = isset($settings['key']) ? $settings['key'] : '';
$auto_install = isset($settings['auto_install']) ? $settings['auto_install'] : '';
//$install_type = isset($settings['install_type']) && $settings['install_type'] == 'symlinked';
$install_type = isset($settings['install_type']) ? $settings['install_type'] : '';
$db_driver = isset($settings['db_driver']) ? $settings['db_driver'] : '';


?>


<form method="POST">
    <div class="row">
        <div class="col-md-6">
            <input type="hidden" name="save_settings" value="1">


            <h2>User installation settings</h2>


            Select language:
            <select name="install_language">
                <option value="en">en</option>
                <option value="bg">bg</option>
                <option value="es">es</option>
            </select>

        </div>

        <div class="col-md-6">
            Online shop enabled?:
            <select name="install_shop">
                <option value="yes">yes</option>
                <option value="no">no</option>
            </select>
        </div>

    </div>


</form>