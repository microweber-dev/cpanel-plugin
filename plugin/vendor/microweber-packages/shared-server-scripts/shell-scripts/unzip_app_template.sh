#!/bin/bash

downloadUrl=$(echo "$1" | base64 -d)

latestFolderTemplate=$2
if [ ! -d "$latestFolderTemplate" ]; then
	mkdir -p "$latestFolderTemplate"
fi

cd "$latestFolderTemplate"

zipDownloadedFile="microweber-template.zip";

echo 'Download from url...'
wget "$downloadUrl" -O "$zipDownloadedFile"

# Unzip selected version
echo 'Unzip file...'
unzip -o $zipDownloadedFile -d $2 > unziping-microweber-template.log

find $latestFolderTemplate -type d -exec chmod 0755 {} \;
find $latestFolderTemplate -type f -exec chmod 0644 {} \;

chcon --user system_u --type httpd_sys_content_t -R $latestFolderTemplate

rm -f $zipDownloadedFile
rm -f "unziping-microweber-template.log"

echo "Done!"
