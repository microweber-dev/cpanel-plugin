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
                        <h5>Your version is: <?php print  $current_version ?>
                               <button name="check_for_update_cms" value="check_for_update_cms" class="btn btn-text btn-xs">  <i class='fa fa-sync'></i>Check for update</button>
                        </h5>
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


                    <?php if (isset($current_templates) and $current_templates and is_array($current_templates)): ?>
                        <h5>Templates <em>(<?php print count($current_templates); ?>)</em>: <?php print implode(', ',$current_templates); ?></h5>
                        <button name="check_marketplace_templates" value="check_marketplace_templates" class="btn btn-info btn-xs">  Marketplace templates</button>
                    <?php endif; ?>


                    <?php if (isset($templates_from_marketplace) and $templates_from_marketplace and is_array($templates_from_marketplace)): ?>
                        <table border="1" class="table-striped table">
                            <?php foreach ($templates_from_marketplace as $template) { ?>
                                <tr>
                                    <?php if (isset($template['latest_version'])) { ?>
                                        <td><?php print  $template['latest_version']['name'] ?></td>
                                        <td><?php print  $template['latest_version']['version'] ?></td>
                                        <td <?php if (isset($current_templates) and $current_templates and is_array($current_templates) and array_search($template['latest_version']['target-dir'],$current_templates)): ?>  class="success"   <?php endif; ?> >
                                            <?php print  $template['latest_version']['target-dir'] ?>
                                         </td>
                                        <td>
                                            <?php if (isset($template['latest_version']['dist'])) { ?>
                                                <a href="<?php print  $template['latest_version']['dist']['url'] ?>"
                                                   target="_blank"><?php print  $template['latest_version']['dist']['type'] ?></a>

                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </table>

<br>
                        <button name="download_userfiles" value="download_userfiles" class="btn btn-primary">Update Templates</button>

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
                        <button name="update_plugin" value="update_plugin" class="btn btn-primary btn-xs">UPDATE
                        </button>

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
