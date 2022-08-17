#!/bin/bash

chownUser=$1
installedAppPath=$2

chown -R $chownUser:$chownUser $installedAppPath/.htaccess
chown -R $chownUser:$chownUser $installedAppPath/*
chown -R $chownUser:$chownUser $installedAppPath/.[^.]*

chmod 755 -R $installedAppPath

find $installedAppPath/storage -type d -exec chmod 750 {} \;
find $installedAppPath/storage -type f -exec chmod 640 {} \;
find $installedAppPath/.env -type f -exec chmod 640 {} \;
find $installedAppPath/config -type d -exec chmod 750 {} \;
find $installedAppPath/config -type f -exec chmod 640 {} \;
