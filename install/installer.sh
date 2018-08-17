#!/bin/bash

echo "Installing Microweber cPanel plugin...";

## Check if being ran by root

username=`whoami`
if [ "$username" != "root" ]; then
    echo "Please run this script as root";
    exit 1
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

register_cp=`/usr/local/cpanel/scripts/install_plugin /usr/local/cpanel/microweber/install/mw-plugin`

if [ -z "$register_cp" ]; then
    echo "Unable to register cPanel plugin"
    exit 1
fi

register_whm=`/usr/local/cpanel/bin/register_appconfig /usr/local/cpanel/microweber/install/microweber.conf`

if [ -z "$register_whm" ]; then
    echo "Unable to register WHM plugin"
    exit 1
fi




step2=`ln -s /usr/local/cpanel/microweber/whm/index.cgi /usr/local/cpanel/whostmgr/docroot/cgi/3rdparty/microweber/index.cgi`

if [ -n "$step2" ]; then
    echo "Unable to complete step 2"
fi

step3=`mkdir /usr/local/cpanel/whostmgr/docroot/3rdparty/microweber`

if [ -n "$step3" ]; then
    echo "Unable to complete step 3"
fi

step4=`ln -s /usr/local/cpanel/microweber/whm/admin.php /usr/local/cpanel/whostmgr/docroot/3rdparty/microweber/admin.php`

if [ -n "$step4" ]; then
    echo "Unable to complete step 4"
fi

step5=`ln -s /usr/local/cpanel/microweber/install/mw-plugin/microweber.png /usr/local/cpanel/whostmgr/docroot/addon_plugins/microweber.png`

if [ -n "$step5" ]; then
    echo "Unable to complete step 5"
fi

step6=`ln -s /usr/local/cpanel/microweber/hooks /var/cpanel/microweber`

if [ -n "$step6" ]; then
    echo "Unable to complete step 6"
fi

## Register WHM hooks

register_hooks=`/usr/local/cpanel/bin/manage_hooks add script /usr/local/cpanel/microweber/hooks/mw_hooks.php`

if [ -z "$register_hooks" ]; then
    echo "Unable to register hooks"
    exit 1
fi