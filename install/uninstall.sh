#!/bin/bash

echo "Uninstalling Microweber cPanel plugin...";

## Check if being ran by root

username=`whoami`
if [ "$username" != "root" ]; then
    echo "Please run this script as root";
    exit 1
fi

unregister_cp=`/usr/local/cpanel/scripts/uninstall_plugin /usr/local/cpanel/microweber/install/mw-plugin`

if [ -z "$unregister_cp" ]; then
    echo "Unable to remove cPanel plugin"
    exit 1
fi

unregister_whm=`/usr/local/cpanel/bin/unregister_appconfig /usr/local/cpanel/microweber/install/microweber.conf`

if [ -z "$unregister_whm" ]; then
    echo "Unable to remove WHM plugin"
    exit 1
fi

unregister_hooks=`/usr/local/cpanel/bin/manage_hooks delete script /usr/local/cpanel/microweber/hooks/mw_hooks.php`

if [ -z "$unregister_hooks" ]; then
    echo "Unable to remove hooks"
    exit 1
fi

## Remove symlinks

echo "Removing symlinks...";

step1=`rm -rf /usr/local/cpanel/whostmgr/docroot/cgi/3rdparty/microweber`

if [ -n "$step1" ]; then
    echo "Unable to complete step 1"
fi

step2=`rm -rf /usr/local/cpanel/whostmgr/docroot/3rdparty/microweber`

if [ -n "$step2" ]; then
    echo "Unable to complete step 2"
fi

step3=`rm -rf /usr/local/cpanel/whostmgr/docroot/addon_plugins/microweber.png`

if [ -n "$step3" ]; then
    echo "Unable to complete step 3"
fi

step4=`rm -rf /var/cpanel/microweber`

if [ -n "$step4" ]; then
    echo "Unable to complete step 4"
fi
