<?php

require_once(__DIR__ . '/MicroweberHelpers.php');


class MicroweberVersionsManager
{
    private $sharedDir = '/usr/share/microweber/latest';
    private $tempZipFile = null;

    public function __construct($sharedDir = null)
    {
        if ($sharedDir) {
            $this->sharedDir = $sharedDir;
        }


        $this->tempZipFile = $this->sharedDir . '/mw-download.zip';

    }

    public function getLatestVersion()
    {
        $url = 'http://update.microweberapi.com/?api_function=get_download_link&get_last_version=1';
        $data = file_get_contents($url);
        $data = json_decode($data);
        if (!$data) return false;
        return $data;
    }

    public function download()
    {
        if ($this->hasDownloaded()) {
           // exec("rm -rf {$this->sharedDir}");
        }

        if(!is_dir($this->sharedDir)){
            MicroweberHelpers::mkdirRecursive($this->sharedDir);
        }


        $latest = $this->getLatestVersion();


        copy($latest->url, $this->tempZipFile);
        //$cmd = "wget -O $this->tempZipFile {$latest->url}";
       // exec($cmd);
        exec("unzip {$this->tempZipFile} -d {$this->sharedDir}");
        unlink($this->tempZipFile);
    }

    public function hasDownloaded()
    {
        return is_dir($this->sharedDir) && file_exists("{$this->sharedDir}/version.txt");
    }
}