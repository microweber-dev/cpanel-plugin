#!/bin/bash

downloadUrl=$(echo "$1" | base64 -d)

latestFolderModule=$2
if [ ! -d "$latestFolderModule" ]; then
	mkdir -p "$latestFolderModule"
fi

cd "$latestFolderModule"

zipDownloadedFile="microweber-module.zip";

echo 'Download from url...'
wget "$downloadUrl" -O "$zipDownloadedFile"

# Unzip selected version
echo 'Unzip file...'
unzip -o $zipDownloadedFile -d $2 > unziping-microweber-module.log

find $latestFolderModule -type d -exec chmod 0755 {} \;
find $latestFolderModule -type f -exec chmod 0644 {} \;

chcon --user system_u --type httpd_sys_content_t -R $latestFolderModule

rm -f $zipDownloadedFile
rm -f "unziping-microweber-module.log"

echo "Done!"
