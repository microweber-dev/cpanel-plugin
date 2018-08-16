<?php

if (!isset($key)) {
    $key = '';
}
if (!isset($current_version)) {
    $current_version = '';

}if (!isset($latest_version)) {
    $latest_version = '';
}
?>
<form method="POST">
    <div>
        <h2>Download Microweber</h2>

        <?php if (!$current_version): ?>
            <h5>You dont have any downloaded version</h5>
        <?php else: ?>
            <h5>Your version is <?php print  $current_version ?></h5>
        <?php endif; ?>



        <?php if ($latest_version): ?>
            <h5>Latest version is <?php print  $latest_version ?></h5>
        <?php endif; ?>



        <div>

            <button name="download_cms" value="download_cms" class="btn">Download Microweber CMS Latest Version</button>
        </div>
    </div>
</form>
<form method="POST">
    <div>
        <h2>White Label Key</h2>

        <div>
            <label>
                Place Your Microweber White Label Key:
                <input type="text" name="key" class="form-control" value="<?php echo $key; ?>">
            </label>

            <div>
                <input name="action" type="submit" value="Save" class="btn btn-primary">


            </div>
        </div>

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
                    <button name="download_userfiles" value="download_userfiles" class="btn">Download Userfiles</button>
                <?php else: ?>
                    <b>
                        Your White Label key is invalid.
                        <a href="https://microweber.com/marketplace#modules">Get your license here.</a>
                    </b>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>


</form>