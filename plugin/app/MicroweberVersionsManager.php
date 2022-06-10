<?php

namespace App;

class MicroweberVersionsManager
{
    public $marketPlaceConnector = null;
    public $marketPlaceConnectorSettings = null;
    public $settings = null;
    public $whmcsConnector = null;

    public $sharedDir = '/usr/share/microweber/latest';
    private $sharedDirTemplate = '/usr/share/microweber/latest/userfiles/templates/';

    private $tempZipFile = null;
    private $tempZipFilePlugin = null;
    private $tempZipFileExtra = null;

    public function __construct($sharedDir = null)
    {
        $this->whmcsConnector = new MicroweberWhmcsConnector();
        $this->whmcsConnectorSettings = $this->whmcsConnector->getSettings();
        $this->marketPlaceConnector = new MicroweberMarketplaceConnector();

        $storage = new MicroweberStorage();
        $this->settings = $storage->read();

        if (isset($this->settings['key']) and !empty($this->settings['key'])) {
            $this->marketPlaceConnector->set_license_key(trim($this->settings['key']));
        }
        if (isset($this->whmcsConnectorSettings['whmcs_url'])) {
            $this->marketPlaceConnector->set_whmcs_url($this->whmcsConnectorSettings['whmcs_url']);
        }

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
                $data = false;
                if ($cache_file) {
                    $data = file_get_contents($cache_file);
                }
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
                $dn = dirname($cache_file);
                if (!is_dir($dn)) {
                    MicroweberHelpers::mkdirRecursive($dn);
                    touch($cache_file);
                }

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
        if (is_dir($this->sharedDir)) {
            $exec = "chmod 755 -R {$this->sharedDir}";
            $output = exec($exec);
        }
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

        $templates = $this->marketPlaceConnector->get_templates_download_urls();

        if (!empty($templates)) {
            foreach ($templates as $template) {
                if (isset($template['download_url'])) {

                    $tempalteZip = $template['target_dir'] . '-latest.zip';
                    $templateDir = $this->sharedDirTemplate . $template['target_dir'] . '/';
                    $downloadDir = $this->sharedDirTemplate . '/tmp/';

                    if (!is_dir($templateDir)) {
                        MicroweberHelpers::mkdirRecursive($templateDir);
                    }

                    if (!is_dir($downloadDir)) {
                        MicroweberHelpers::mkdirRecursive($downloadDir);
                    }

                    //  $templateZipFullpath = $templateDir . $tempalteZip;
                    $templateZipFullpath = $downloadDir . $tempalteZip;

                    MicroweberHelpers::download($template['download_url'], $templateZipFullpath);

                    if (!function_exists('exec')) {
                        echo "exec is disabled";
                        exit;
                    }


                    if (is_dir($templateDir)) {
                        exec("rm -rf {$templateDir}/*");
                    }

                    if (!is_dir($templateDir)) {
                        MicroweberHelpers::mkdirRecursive($templateDir);
                        exec("chmod 755 -R {$templateDir}");

                    }

                    exec("unzip -o {$templateZipFullpath} -d {$templateDir}", $output);
                    unlink($templateZipFullpath);
                }
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

    public function getSupportedLanguages()
    {

        $sharedDir = $this->sharedDir;


        $languages = array();

        // $lang_dir = $sharedDir . '/userfiles/modules/microweber/language';
        $lang_dir = $sharedDir . '/src/MicroweberPackages/Translation/resources/lang';

        if (is_dir($lang_dir)) {

            $listDir = scandir($lang_dir);

            foreach ($listDir as $file) {
                $ext = MicroweberHelpers::getFileExtension($file);
                if ($ext == 'json') {

                    $upperText = $file;
                    $upperText = str_replace('.json', false, $file);
                    //var_dump($upperText);
                    //     $upperText = strtoupper($upperText);

                    // $languages[trim(strtolower($upperText))] = trim($upperText);
                    //  $languages[trim(strtolower($upperText))] = trim($upperText);
                    $languages[$upperText] = trim($upperText);
                }
            }
        }
        return $languages;

    }

    public function templatesList($sharedDir = false)
    {
        if (!$sharedDir) {
            $sharedDir = $this->sharedDir;
        }

        if (is_dir($sharedDir) and file_exists("{$sharedDir}/version.txt")) {
            if (is_dir("{$sharedDir}/userfiles/templates")) {
                $dirs = glob("{$sharedDir}/userfiles/templates/*", GLOB_ONLYDIR);
                if ($dirs) {
                    $return = array();
                    foreach ($dirs as $index => $dir) {
                        $return[] = basename($dir);
                    }

                    return $return;
                }
            }
        }
        return;
    }
}
