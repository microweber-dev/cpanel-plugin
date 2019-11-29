<?php

if (!isset($settings) or !$settings) {
    $settings = array();
}
$white_label_key = isset($settings['key']) ? $settings['key'] : '';
$auto_install = isset($settings['auto_install']) ? $settings['auto_install'] : '';
//$install_type = isset($settings['install_type']) && $settings['install_type'] == 'symlinked';
$install_type = isset($settings['install_type']) ? $settings['install_type'] : '';
$db_driver = isset($settings['db_driver']) ? $settings['db_driver'] : '';
$language = isset($settings['language']) ? $settings['language'] : 'en';
$supported_langs = isset($supported_langs) ? $supported_langs : '';


?>

<form method="POST">
    <div class="row">
        <div class="col-md-4">
            <input type="hidden" name="save_settings" value="1">

            <h2>Installation settings</h2>

            <div>
                <label>
                    <input type="radio" name="auto_install" value="1" <?php echo $auto_install == '1' ? 'checked' : ''; ?>>
                    Automatically install Microweber on new domains creation. <a href="#" data-toggle="tooltip" title="You must enable the Microweber feature in your packages settings">[?]</a>
                </label>
                <br>

                <label>
                    <input type="radio" name="auto_install" value="0" <?php echo $auto_install == '0' ? 'checked' : ''; ?>>
                    Allow users to Manually install Microweber from cPanel. <a href="#" data-toggle="tooltip" title="You must enable the Microweber feature in your packages settings">[?]</a>
                </label>
                <br>

                <label><input type="radio" name="auto_install" <?php echo $auto_install == 'disabled' ? 'checked' : ''; ?> value="disabled">Disabled for all users</label>
                <br>
            </div>
        </div>

        <div class="col-md-4">
            <h2>Installation Type</h2>

            <div>
                <label>
                    <input type="radio" name="install_type" value="normal" <?php if ($install_type != 'symlinked') {
                        print 'checked';
                    } ?>>
                    Default <a href="#" data-toggle="tooltip" title="All code is copied in the folder of the user. ">[?]</a>
                </label>
                <br>
                <label>

                    <input type="radio" name="install_type"
                           value="symlinked" <?php if ($install_type == 'symlinked') {
                        print 'checked';
                    } ?> >
                    Sym-linked (saves a big amount of disk space) <a href="#" data-toggle="tooltip" title="Code is symliked from shared folder for all users">[?]</a>
                </label>
                <br>
            </div>



            <div>


                <h2>Database Driver</h2>
                <label>
                    <select name="db_driver" class="form-control" style="width:230px;">

                        <option <?php if ($db_driver == 'mysql') {
                            print 'selected';
                        } ?> value="mysql">MySQL
                        </option>
                        <option <?php if ($db_driver == 'sqlite') {
                            print 'selected';
                        } ?> value="sqlite">SQLite
                        </option>
                    </select>
                </label>

                <?php if($supported_langs){ ?>
                <h2>Language</h2>
                <label>
                    <select name="language" class="form-control" style="width:230px;">
                        <option <?php if (!$language) {     print 'selected'; } ?> value="">Default</option>

                        <?php foreach($supported_langs as $supported_lang){ ?>
                        <option <?php if ($language == $supported_lang) {
                            print 'selected';
                        } ?> value="<?php print ($supported_lang) ?>"><?php print strtoupper($supported_lang) ?>
                        </option>
                        <?php  } ?>
                    </select>
                </label>

                <?php  } ?>

            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
        <button type="submit" class="btn btn-primary" style="margin-top: 15px; width:230px; margin-left: 2px;">Save</button>
        </div>
    </div>
</form>