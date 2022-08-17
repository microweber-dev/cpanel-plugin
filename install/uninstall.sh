#!/bin/bash

echo "Uninstalling Microweber cPanel plugin...";

## Check if being ran by root

username=`whoami`
if [ "$username" != "root" ]; then
    echo "Please run this script as root";
fi

/bin/rm -rf /usr/local/cpanel/whostmgr/docroot/cgi/3rdparty/microweber
/bin/rm -rf /usr/local/cpanel/base/frontend/paper_lantern/microweber
/bin/rm -rf /usr/local/cpanel/base/frontend/jupiter/microweber
/bin/rm -rf /usr/local/cpanel/whostmgr/docroot/3rdparty/microweber
/bin/rm -rf /usr/local/cpanel/whostmgr/docroot/addon_plugins/microweber.png
/bin/rm -rf /var/cpanel/microweber
/bin/rm -rf /usr/local/cpanel/whostmgr/docroot/cgi/microweber
/bin/rm -rf /usr/local/cpanel/whostmgr/docroot/templates/microweber

/usr/local/cpanel/scripts/uninstall_plugin /usr/local/cpanel/microweber/install/mw-plugin
/usr/local/cpanel/bin/unregister_appconfig /usr/local/cpanel/microweber/install/microweber.conf
/usr/local/cpanel/bin/manage_hooks delete script /usr/local/cpanel/microweber/hooks/mw_hooks.php