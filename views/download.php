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
            <div class="row">
                <div class="col-md-4">
                    <h2>Microweber version</h2>

                    <?php if (!$current_version): ?>
                        <h5>You dont have any downloaded version</h5>
                    <?php else: ?>
                        <h5>Your version is: <?php print  $current_version ?></h5>
                    <?php endif; ?>

                    <?php if ($latest_version): ?>
                        <h5>Latest version is: <?php print  $latest_version ?></h5>
                    <?php endif; ?>

                    <?php if (version_compare($latest_version, $current_version, '>')): ?>
                        <button name="download_cms" value="download_cms" class="btn btn-primary btn-xs">UPDATE</button>

                        <script>
                            $(document).ready(function () {
                                $('.js-update-cms').show();
                            });
                        </script>
                    <?php endif; ?>

                    <?php if ($last_download_date): ?>
                        <h5>Last download date: <?php print  $last_download_date ?></h5>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <h2>cPanel Microweber Plugin version</h2>

                    <?php if ($current_plugin_version): ?>
                        <h5>Your plugin version is: <?php print  $current_plugin_version ?></h5>
                    <?php endif; ?>

                    <?php if ($latest_plugin_version): ?>
                        <h5>Latest plugin version is: <?php print  $latest_plugin_version ?></h5>
                    <?php endif; ?>

                    <?php if (version_compare($latest_plugin_version, $current_plugin_version, '>')): ?>
                        <button name="update_plugin" value="update_plugin" class="btn btn-primary btn-xs">UPDATE</button>

                        <script>
                            $(document).ready(function () {
                                $('.js-update-plugin').show();
                            });
                        </script>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>
