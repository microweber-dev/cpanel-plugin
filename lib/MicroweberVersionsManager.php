<?php

require_once(__DIR__ . '/MicroweberHelpers.php');


class MicroweberVersionsManager
{
    private $sharedDir = '/usr/share/microweber/latest';
    private $pluginDir = '/usr/local/cpanel/microweber';
    private $tempZipFile = null;
    private $tempZipFilePlugin = null;
    private $tempZipFileExtra = null;

    public function __construct($sharedDir = null)
    {
        if ($sharedDir) {
            $this->sharedDir = $sharedDir;
        }


        $this->tempZipFile = $this->sharedDir . '/mw-download.zip';
        $this->tempZipFilePlugin = $this->sharedDir . '/mw-cpanel-plugin.rpm';
        $this->tempZipFileExtra = $this->sharedDir . '/mw-extra.zip';

    }

    public function getCurrentVersionLastDownloadDateTime()
    {
        $version_file = file_exists($this->sharedDir . "/version.txt");

        if ($version_file) {
            $version = filectime($this->sharedDir . "/version.txt");
            if ($version) {
                return date('Y-m-d H:i:s', $version);
            }
        }
    }

    public function getCurrentVersion()
    {

        $version_file = file_exists($this->sharedDir . "/version.txt");
        $version = 'unknown';
        if ($version_file) {
            $version = file_get_contents($this->sharedDir . "/version.txt");
            $version = strip_tags($version);
        }
        return $version;
    }


    public function getCurrentPluginVersion()
    {

        $f = __DIR__ . DIRECTORY_SEPARATOR . '../version.txt';
        $version_file = file_exists($f);
        $version = 'unknown';
        if ($version_file) {
            $version = file_get_contents($f);
            $version = strip_tags($version);
        }
        return $version;
    }

    public function getLatestVersion($force_check = false)
    {
        $data = $this->getLatestVersionData($force_check);
        if (isset($data['version'])) {
            return $data['version'];
        }
    }

    public function getLatestPluginVersion($force_check = false)
    {
        $data = $this->getLatestVersionData($force_check);
        if (isset($data['plugin']) and isset($data['plugin']) and isset($data['plugin']['version'])) {
            return $data['plugin']['version'];
        }
    }


    public function getLatestVersionData($force_check = false)
    {
        $cache_file = '/usr/share/microweber/version_check_cache.txt';


        $current_time = time();

        $update_cache = false;
        if ($force_check) {
            $update_cache = true;
        } elseif (!is_file($cache_file)) {
            $update_cache = true;
        } else if (filemtime($cache_file) and (filemtime($cache_file) < $current_time - 3600)) {
            $update_cache = true;
        }


        if (!$update_cache) {

            if (is_file($cache_file) and !is_writable($cache_file)) {
                $update_cache = false;
            } else {

                $data = file_get_contents($cache_file);

                if (!$data) {
                    $update_cache = true;
                }
            }
        }

        if ($update_cache) {
            $url = 'http://update.microweberapi.com/?api_function=get_download_link&get_last_version=1';
            $url2 = 'http://update.microweberapi.com/?api_function=get_download_link&type=3rdparty/cpanel&get_last_version=1';


            $data = file_get_contents($url);

            if (!$data) {
                return false;
            }


            $data = @json_decode($data, true);
            $data2 = file_get_contents($url2);
            $data2 = @json_decode($data2, true);

            if ($data and $data2) {
                $data['plugin'] = $data2;
            }
            $data = json_encode($data);

            if ($cache_file and $data) {
                $fp = fopen($cache_file, 'w+');
                fwrite($fp, $data);
                fclose($fp);
            }
        }


        if (!$data) return false;


        $data = @json_decode($data, true);


        return $data;
    }


    public function download()
    {
        if ($this->hasDownloaded()) {
            // exec("rm -rf {$this->sharedDir}");
        }

        if (!is_dir($this->sharedDir)) {
            MicroweberHelpers::mkdirRecursive($this->sharedDir);
        }


        $latest = $this->getLatestVersionData();



        if (!isset($latest['url'])) {
            return;
        }

        MicroweberHelpers::download($latest['url'], $this->tempZipFile);
        //$cmd = "wget -O $this->tempZipFile {$latest->url}";
        // exec($cmd);
        exec("unzip -o {$this->tempZipFile} -d {$this->sharedDir}");
        unlink($this->tempZipFile);
    }

    public function hasDownloaded()
    {
        return is_dir($this->sharedDir) && file_exists("{$this->sharedDir}/version.txt");
    }

    public function downloadExtraContent($key)
    {
        if (!$key) {
            return;
        }

        $url = 'http://update.microweberapi.com/?api_function=get_download_link&get_extra_content=1&license_key=' . urlencode($key);

        $data = @file_get_contents($url);
        if ($data) {
            $data = @json_decode($data, true);
            if (isset($data['url'])) {

                MicroweberHelpers::download($data['url'], $this->tempZipFileExtra);
                exec("unzip -o {$this->tempZipFileExtra} -d {$this->sharedDir}");
                unlink($this->tempZipFileExtra);

            }
        }


    }

    public function isSymlinked()
    {
        $is_symlink = is_link($this->sharedDir . "/src");
        return $is_symlink;
    }


    public function downloadPlugin()
    {


        $data = $this->getLatestVersionData();

        if (isset($data['plugin'])
            and isset($data['plugin'])
            and isset($data['plugin']['version'])
            and isset($data['plugin']['url'])

        ) {
            if (is_file($this->tempZipFilePlugin)) {
                unlink($this->tempZipFilePlugin);
            }
            $url = $data['plugin']['url'];

            MicroweberHelpers::download($url, $this->tempZipFilePlugin);


            if (is_file($this->tempZipFilePlugin)) {
                $update = 'rpm -Uvh --force ' . $this->tempZipFilePlugin;
                exec($update);
             }

        }

    }
}