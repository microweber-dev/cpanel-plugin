<?php

if (!isset($domains)) {
    return;
}
?>

<div class="instance-list">
    <table class="table table-striped responsive-table">
        <thead>
        <tr>
            <th>Domain</th>
            <th>Version</th>
            <th>File Path</th>
            <th class="text-right">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php $siteCount = 0; ?>
        <?php if ($domains): ?>
            <?php foreach ($domains as $key => $domain): ?>
                <?php
                $mainDir = $domain['documentroot'];


                if (isset($_GET['search'])) {
                    if (strpos($domain['domain'], $_GET['search']) === false) continue;
                }
                $siteCount++;
                ?>
                <tr>
                    <td>
                        <a href="http://<?php echo $domain['domain']; ?>" target="_blank">
                            <img src="./microweber/mw-icon.png"
                                 class="mw-icon"> <?php echo $domain['domain']; ?>
                        </a>
                    </td>
                    <td><?php echo $domain['version']; ?></td>
                    <td><?php echo $domain['documentroot']; ?></td>
                    <td class="action">
                        <a href="#" class="update">Update</a>
                        <a href="#" class="login">Login</a>

                        <button type="button" class="remove" data-toggle="modal"
                                data-target="#removeSite-<?php print $key; ?>"><i class="fa fa-trash"></i>
                        </button>

                        <!-- Modal Delete Accept -->
                        <div class="modal fade" id="removeSite-<?php print $key; ?>" tabindex="-1"
                             role="dialog" aria-labelledby="removeSiteLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-body" style="padding: 70px 0;">
                                            <h4 class="modal-title text-center">Are you sure you want to
                                                delete this website?</h4>

                                            <input type="hidden" name="_action" value="uninstall">
                                            <input type="hidden" name="domain"
                                                   value="<?php echo htmlspecialchars(json_encode($domain)); ?>">
                                        </div>

                                        <div class="modal-footer" style="margin: 0;">
                                            <button type="button" class="btn btn-primary"
                                                    data-dismiss="modal">No
                                            </button>
                                            <button type="submit" class="btn btn-default">Yes, delete my
                                                website
                                            </button>
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

    <?php if (!isset($_GET['search']) && $siteCount == 0): ?>
        <div id="row-no-instances" class="instance-list-callout callout callout-info">
            <i class="fa fa-exclamation-circle"></i>
                            <span id="no-installation-msg" class="callout-message">
                            There is no Microweber installations yet.
                            <a id="addinstall" href="#install" title="Create a new Microweber installation.">Create an
                                installation.</a>
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