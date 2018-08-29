<?php

if (!isset($key)) {
    $key = '';
}
if (!isset($current_version)) {
    $current_version = '';

}
if (!isset($latest_version)) {
    $latest_version = '';
}

if (!isset($last_download_date)) {
    $last_download_date = '';
}
if (!isset($current_plugin_version)) {
    $current_plugin_version = '';
}
if (!isset($latest_plugin_version)) {
    $latest_plugin_version = '';
}


?>

<div class="row">

    <div class="col-sm-4">
        <form method="POST">
            <?php if (isset($key_data) and isset($key_data['status'])): ?>
                <div class="callout callout-cpanel">
                    <?php if ($key_data['status'] == 'active'): ?>
                        <p><b><?php echo $key_data['rel_name']; ?></b></p>
                        <p>
                            <b><?php echo $key_data['registered_name']; ?></b>,
                            <?php echo $key_data['company_name']; ?>
                        </p>
                        <p>
                            License active from
                            <b><?php echo date('d M Y', strtotime($key_data['reg_on'])); ?></b>
                            to
                            <b><?php echo date('d M Y', strtotime($key_data['due_on'])); ?></b>
                        </p>
                        <p>Billing cycle: <?php echo $key_data['billing_cycle']; ?></p>
                        <button name="download_userfiles" value="download_userfiles" class="btn btn-default">Update Premium Templates</button>
                    <?php else: ?>
                        <b>
                            Your White Label key is invalid.
                            <a href="https://microweber.com/marketplace#modules">Get your license here.</a>
                        </b>
                    <?php endif; ?>

                    <a href="javascript:;" class="btn btn-success js-show-white-label">Update your White label key</a>
                </div>
            <?php endif; ?>

            <div class="js-white-label" <?php if (isset($key_data) and isset($key_data['status']) and $key_data['status'] == 'active'): ?>style="display: none;"<?php endif; ?>>
                <h2>White Label Key</h2>
                <div class="row">
                    <div class="col-xs-8">

                        <div class="form-group">
                            <label class="control-label" for="key">Place Your Microweber White Label Key:</label>
                            <input class="form-control" name="key" id="key" type="text" value="<?php echo $key; ?>">
                        </div>
                    </div>

                    <div class="col-xs-4">
                        <input name="action" type="submit" value="Save" class="btn btn-primary" style="margin-top: 25px;">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-sm-8">
        <form class="form-horizontal">
            <input type="hidden" name="_save_branding" />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="brand_name" class="col-lg-4 control-label">Brand Name</label>
                        <div class="col-lg-8">
                            <input type="text" name="brand_name" class="form-control" id="brand_name" placeholder="Enter the name of your company">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="admin_logo_login_link" class="col-lg-4 control-label">Admin login - White Label URL ?</label>
                        <div class="col-lg-8">
                            <input type="text" name="admin_logo_login_link" class="form-control" id="admin_logo_login_link" placeholder="Enter website url of your company">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="custom_support_url" class="col-lg-4 control-label">Enable support links ?</label>
                        <div class="col-lg-8">
                            <input type="text" name="custom_support_url" class="form-control" id="custom_support_url" placeholder="Enter url of your contact page">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="enable_service_links"> Enable
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="brand" class="col-lg-4 control-label">Enable "Powered by"</label>
                        <div class="col-lg-8">
                            <textarea name="brand_name" class="form-control" rows="3" id="textArea"></textarea>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="brand_name"> Enable
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="brand" class="col-lg-4 control-label">Brand Name</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="brand" placeholder="Enter the name of your company">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="brand" class="col-lg-4 control-label">Admin login - White Label URL ?</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="brand" placeholder="Enter website url of your company">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="brand" class="col-lg-4 control-label">Enable support links ?</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="brand" placeholder="Enter url of your contact page">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Enable
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="brand" class="col-lg-4 control-label">Enable "Powered by"</label>
                        <div class="col-lg-8">
                            <textarea class="form-control" rows="3" id="textArea"></textarea>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Enable
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
