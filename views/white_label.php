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
                        <button name="download_userfiles" value="download_userfiles" class="btn btn-primary">Update Premium Templates</button>
                    <?php else: ?>
                        <b>
                            Your White Label key is invalid.
                            <a href="https://microweber.com/marketplace#modules">Get your license here.</a>
                        </b>
                    <?php endif; ?>

                    <a href="javascript:;" class="btn btn-link js-show-white-label">Change your White label key</a>
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
        <form class="form-horizontal1" method="post">
            <input type="hidden" name="_action" value="_save_branding"/>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="brand_name" class="control-label">Brand Name</label>
                        <input type="text" name="brand_name" class="form-control" id="brand_name" placeholder="Enter the name of your company" value="<?= isset($branding['brand_name']) ? $branding['brand_name'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="admin_logo_login_link" class="control-label">Admin login - White Label URL ?</label>
                        <input type="text" name="admin_logo_login_link" class="form-control" id="admin_logo_login_link" placeholder="Enter website url of your company" value="<?= isset($branding['admin_logo_login_link']) ? $branding['admin_logo_login_link'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="custom_support_url" class="control-label">Enable support links ?</label>
                        <input type="text" name="custom_support_url" class="form-control" id="custom_support_url" placeholder="Enter url of your contact page" value="<?= isset($branding['custom_support_url']) ? $branding['custom_support_url'] : ''; ?>">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="enable_service_links" value="1" <?= (isset($branding['enable_service_links']) AND $branding['enable_service_links'] == 1) ? 'checked' : ''; ?>> Enable
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="powered_by_link" class="control-label">Enable "Powered by"</label>
                        <textarea name="powered_by_link" class="form-control" rows="3" id="powered_by_link"><?= isset($branding['powered_by_link']) ? $branding['powered_by_link'] : ''; ?></textarea>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="disable_powered_by_link" value="1" <?= (isset($branding['disable_powered_by_link']) AND $branding['disable_powered_by_link'] == 1) ? 'checked' : ''; ?>> Hide "Powered by" link
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label for="logo_admin" class="control-label">Logo for Admin panel (size: 180x35 px)</label>
                        <input type="text" name="logo_admin" class="form-control" id="logo_admin" value="<?= isset($branding['logo_admin']) ? $branding['logo_admin'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="logo_live_edit" class="control-label">Logo for Live-Edit toolbar (size: 50x50 px)</label>
                        <input type="text" name="logo_live_edit" class="form-control" id="logo_live_edit" value="<?= isset($branding['logo_live_edit']) ? $branding['logo_live_edit'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="logo_login" class="control-label">Logo for Login screen (max width 290px)</label>
                        <input type="text" name="logo_login" class="form-control" id="logo_login" value="<?= isset($branding['logo_login']) ? $branding['logo_login'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="disable_marketplace" value="1" <?= (isset($branding['disable_marketplace']) AND $branding['disable_marketplace'] == 1) ? 'checked' : ''; ?>> Disable Microweber Marketplace
                            </label>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="brand_name" class="control-label">WHMCS Intragration</label>
                        <input type="text" name="whmcs_url" class="form-control" id="whmcs_url"   placeholder="Enter the URL of your WHMCS" value="<?= isset($branding['whmcs_url']) ? $branding['whmcs_url'] : ''; ?>">
<br>
                        <small>You must install the  Microweber WHMCS addon from <a href="https://github.com/microweber-dev/whmcs-plugin" target="_blank">this link</a> and then Enter the url of WHMCS for example: https://members.microweber.com/ </small>

                    </div>








                    <div class="">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
