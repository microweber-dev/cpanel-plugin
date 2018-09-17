<?php

if (!isset($domains)) {
    return;
}
if (!isset($admin_view)) {
    $admin_view = false;
}

$siteCount = 0;
if ($domains and !empty($domains)) {
    $siteCount = count($domains);
}

?>

<div class="instance-list">
    <table class="table table-striped responsive-table">
        <thead>
        <tr>
            <th>Domain</th>
            <th></th>
            <?php if ($admin_view): ?>
                <th>User</th>
            <?php endif; ?>
            <th>Version</th>
            <th>Created at</th>
            <th class="text-center">Type</th>
            <th>File Path</th>
            <?php if (!$admin_view): ?>
                <th class="text-right">Actions</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php if ($domains): ?>
            <?php foreach ($domains as $key => $domain): ?>
                <?php
                $mainDir = $domain['documentroot'];


                if (isset($_GET['search'])) {
                    if (strpos($domain['domain'], $_GET['search']) === false) continue;
                }
                ?>
                <tr>
                    <td>
                        <a href="http://<?php echo $domain['domain']; ?>" target="_blank">
                            <!--                            <img src="./microweber/mw-icon.png"       class="mw-icon" /> -->
                            <?php echo $domain['domain']; ?>
                        </a>
                    </td>
                    <td class="text-right"> <?php if (isset($domain['type']) and $domain['type']): ?>
                            <span class="label <?php if ($domain['type'] == 'main_domain') {
                                echo 'label-success';
                            } else {
                                echo 'label-primary';
                            }; ?>" title="<?php echo $domain['type']; ?>" style="margin-left:10px;"><?php echo MicroweberHelpers::titlelize($domain['type']); ?></span>
                        <?php endif; ?>
                    </td>

                    <?php if ($admin_view): ?>
                        <td><?php echo $domain['user']; ?></td>
                    <?php endif; ?>

                    <td><?php echo $domain['version']; ?></td>
                    <td><?php echo date('Y M d', strtotime($domain['created_at'])); ?></td>
                    <td class="text-center">
                        <?php if ($admin_view and isset($domain['is_symlink']) and $domain['is_symlink']): ?>
                            <span class="label label-default" title="<?php echo $domain['symlink_target']; ?>">symlink</span>
                        <?php else: ?>
                            <span class="label label-warning" title="copy of source">standalone</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $domain['documentroot']; ?></td>
                    <td class="action text-right">
                        <?php if ($admin_view): ?>

                            <?php

                            /*        <form method="POST" id="updateSite-<?php print $key; ?>">
                                <input type="hidden" name="_action" value="_do_update">
                                <input type="hidden" name="domain"
                                       value="<?php echo htmlspecialchars(json_encode($domain)); ?>">
                                <button type="submit"
                                        target="#updateSite-<?php print $key; ?>"><i class="fa fa-caret-square-up"></i> Update
                                </button>
                            </form>*/

                            ?>
                        <?php endif; ?>


                        <!--                        <a href="#" class="update">Update</a>-->
                        <!--                        <a href="#" class="login">Login</a>-->


                        <?php if (!$admin_view): ?>
                            <button type="button" class="btn btn-danger remove" data-toggle="modal" data-target="#removeSite-<?php print $key; ?>"><i class="fa fa-trash"></i> Remove</button>
                        <?php endif; ?>

                        <!-- Modal Delete Accept -->
                        <div class="modal fade" id="removeSite-<?php print $key; ?>" tabindex="-1"
                             role="dialog" aria-labelledby="removeSiteLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-body" style="padding: 80px 0;">
                                            <h4 class="modal-title text-center">Are you sure you want to delete this website?</h4>

                                            <input type="hidden" name="_action" value="uninstall">
                                            <input type="hidden" name="domain" value="<?php echo htmlspecialchars(json_encode($domain)); ?>">
                                        </div>

                                        <div class="modal-footer" style="margin: 0;">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                            <button type="submit" class="btn btn-danger">Yes, delete my website</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>


    <p>You have <?php print $siteCount ?> installations</p>


    <?php if (!isset($_GET['search']) && $siteCount == 0): ?>
        <div id="row-no-instances" class="instance-list-callout callout callout-info">
            <i class="fa fa-exclamation-circle"></i>
            <span id="no-installation-msg" class="callout-message">
                            There is no Microweber installations yet. <a id="addinstall" href="#install" title="Create a new Microweber installation.">Create aninstallation.</a>
                        </span>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['search']) && $siteCount == 0): ?>
        <div id="row-no-matches" class="instance-list-callout callout callout-info">
            <i class="fa fa-exclamation-circle"></i>
            No Microweber installations match your search criteria.
        </div>
    <?php endif; ?>

    <?php if ($siteCount > 10): ?>
        <div id="loading-callout-large-set" class="instance-list-callout callout callout-warning">
            <i class="fa fa-exclamation-circle"></i>
            This account contains many Microweber installations. Some operations may require more time.
        </div>
    <?php endif; ?>
</div>
<br> <br>