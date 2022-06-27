#!/bin/bash

echo "Installing Microweber cPanel plugin...";

## Check if being ran by root

username=`whoami`
if [ "$username" != "root" ]; then
    echo "Please run this script as root";
    exit 1
fi

## Create plugin key generate
plugin_key_generate=`cd /usr/local/cpanel/microweber/plugin && cp .env.example .env && php artisan key:generate`
if [ -n "$plugin_key_generate" ]; then
    echo "Unable to make plugin_key_generate"
    echo "$plugin_key_generate"
fi

find /usr/local/cpanel/microweber/plugin/vendor/microweber-packages/shared-server-scripts/shell-scripts -type f -iname "*.sh" -exec chmod +x {} \;

## Create plugin database
plugin_database=`touch /usr/local/cpanel/microweber/plugin/database/database.sqlite`
if [ -n "$plugin_database" ]; then
    echo "Unable to make plugin_database"
    echo "$plugin_database"
fi

## Create plugin migrate
plugin_migrate=`cd /usr/local/cpanel/microweber/plugin && php artisan migrate`
if [ -n "$plugin_migrate" ]; then
    echo "Unable to make plugin_migrate"
    echo "$plugin_migrate"
fi

## Create symlinks
echo "Creating dirs...";

step1=`mkdir -p /usr/local/cpanel/whostmgr/docroot/cgi/3rdparty/microweber/`
if [ -n "$step1" ]; then
    echo "Unable to complete step mkdir /usr/local/cpanel/whostmgr/docroot/cgi/3rdparty/microweber/"
    echo "$step1"
fi

step12=`mkdir -p /usr/local/cpanel/microweber/`
if [ -n "$step12" ]; then
    echo "Unable to complete step mkdir /usr/local/cpanel/microweber/"
    echo "$step1"
fi

step13=`mkdir -p /usr/local/cpanel/microweber/storage`
if [ -n "$step12" ]; then
    echo "Unable to complete step mkdir /usr/local/cpanel/microweber/storage"
    echo "$step1"
fi

chmod_files=`chmod +x -R /usr/local/cpanel/microweber`

if [ -n "$chmod_files" ]; then
    echo "Unable to CHMOD the cPanel plugin"
fi



unregister_cp=`/usr/local/cpanel/scripts/uninstall_plugin /usr/local/cpanel/microweber/install/mw-plugin`

if [ -z "$unregister_cp" ]; then
    echo "Cleaning up cPanel plugin"
fi

unregister_whm=`/usr/local/cpanel/bin/unregister_appconfig /usr/local/cpanel/microweber/install/microweber.conf`

if [ -z "$unregister_whm" ]; then
    echo "Cleaning up WHM plugin"
fi

unregister_hooks=`/usr/local/cpanel/bin/manage_hooks delete script /usr/local/cpanel/microweber/hooks/mw_hooks.php`

if [ -z "$unregister_hooks" ]; then
    echo "Cleaning up hooks"
fi



register_cp=`/usr/local/cpanel/scripts/install_plugin /usr/local/cpanel/microweber/install/mw-plugin`

if [ -z "$register_cp" ]; then
    echo "Unable to register cPanel plugin"
fi

register_whm=`/usr/local/cpanel/bin/register_appconfig /usr/local/cpanel/microweber/install/microweber.conf`
if [ -z "$register_whm" ]; then
    echo "Unable to register WHM plugin"
fi


step2=`ln -sfn /usr/local/cpanel/microweber/plugin/public /usr/local/cpanel/whostmgr/docroot/cgi/microweber`
if [ -n "$step2" ]; then
    echo "Unable to complete step 2"
fi

step21=`ln -sfn /usr/local/cpanel/microweber/microweber.live.php /usr/local/cpanel/base/frontend/paper_lantern/microweber.live.php`
if [ -n "$step21" ]; then
    echo "Unable to complete step 2-1"
fi


step5=`ln -sfn /usr/local/cpanel/microweber/install/mw-plugin/microweber.png /usr/local/cpanel/whostmgr/docroot/addon_plugins/microweber.png`
if [ -n "$step5" ]; then
    echo "Unable to complete step 5"
fi


step6=`ln -sfn /usr/local/cpanel/microweber/hooks /var/cpanel/microweber`
if [ -n "$step6" ]; then
    echo "Unable to complete step 6"
fi

## Register WHM hooks
register_hooks=`/usr/local/cpanel/bin/manage_hooks add script /usr/local/cpanel/microweber/hooks/mw_hooks.php`
if [ -z "$register_hooks" ]; then
    echo "Unable to register hooks"
fi