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
    <div class="col-sm-12">
        <form method="POST">
            <div>
                <h2>Download Microweber</h2>

                <?php if (!$current_version): ?>
                    <h5>You dont have any downloaded version</h5>
                <?php else: ?>
                    <h5>Your version is: <?php print  $current_version ?></h5>
                <?php endif; ?>

                <?php if ($latest_version): ?>
                    <h5>Latest version is: <?php print  $latest_version ?></h5>
                <?php endif; ?>

                <div>
                    <button name="download_cms" value="download_cms" class="btn btn-primary">Download Microweber CMS LatestVersion</button>
                </div>

                <?php if ($last_download_date): ?>
                    <h5>Last download date: <?php print  $last_download_date ?></h5>
                <?php endif; ?>

                <?php if ($current_plugin_version): ?>
                    <h5>Your plugin version is: <?php print  $current_plugin_version ?></h5>
                <?php endif; ?>
                <?php if ($latest_plugin_version): ?>
                    <h5>Latest plugin version is: <?php print  $latest_plugin_version ?></h5>
                <?php endif; ?>

                <?php if (version_compare($latest_plugin_version, $current_plugin_version, '>')): ?>
                    <div>
                        <button name="update_plugin" value="update_plugin" class="btn btn-primary">Update plugin</button>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
