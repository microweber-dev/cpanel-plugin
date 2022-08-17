<?php

namespace MicroweberPackages\SharedServerScripts;

use MicroweberPackages\SharedServerScripts\FileManager\Adapters\NativeFileManager;
use MicroweberPackages\SharedServerScripts\Shell\Adapters\NativeShellExecutor;

class MicroweberAppPathHelper
{
    /**
     * @var
     */
    public $path;

    /**
     * @var NativeFileManager
     */
    public $fileManager;

    /**
     * @var NativeShellExecutor
     */
    public $shellExecutor;

    public function __construct()
    {
        $this->fileManager = new NativeFileManager();
        $this->shellExecutor = new NativeShellExecutor();
    }


    /**
     * @param $adapter
     * @return void
     */
    public function setShellExecutor($adapter)
    {
        $this->shellExecutor = $adapter;
    }

    /**
     * @param $adapter
     * @return void
     */
    public function setFileManager($adapter)
    {
        $this->fileManager = $adapter;
    }

    /**
     * @param $path
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return bool
     */
    public function isSymlink()
    {
        return $this->fileManager->isLink($this->path.'/vendor');
    }

    /**
     * @return bool
     */
    public function isInstalled()
    {
        $configFile = $this->path . '/config/microweber.php';
        if ($this->fileManager->fileExists($configFile)) {
            return true;
        }

        return false;
    }

    /**
     * @return false|string
     */
    public function getCreatedAt()
    {
        $configFile = $this->path . '/config/app.php';
        if ($this->fileManager->fileExists($configFile)) {
            return date("Y-m-d H:i:s", $this->fileManager->filectime($configFile));
        }

        return false;
    }

    /**
     * @return string
     */
    public function getCurrentVersion()
    {
        $versionFile = $this->fileManager->fileExists($this->path . '/version.txt');

        $version = 'unknown';
        if ($versionFile) {
            $version = $this->fileManager->fileGetContents($this->path . '/version.txt');
            $version = strip_tags($version);
        }

        return $version;
    }

    /**
     * @return array
     */
    public function getSupportedModules()
    {
        try {
            $executeArtisan = $this->shellExecutor->executeCommand([
                'php',
                '-d memory_limit=512M',
                $this->path . '/artisan',
                'microweber:get-modules',
            ]);
            $decodeArtisanOutput = json_decode($executeArtisan, true);
            if (!empty($decodeArtisanOutput)) {
                return $decodeArtisanOutput;
            }
        } catch (\Exception $e) {

        }

        return [];
    }

    /**
     * @return array
     */
    public function getSupportedTemplates()
    {

        if (!$this->isInstalled()) {

            $templates = [];
            $templatePath = $this->path . '/userfiles/templates';
            if (!$this->fileManager->isDir($templatePath)) {
                return [];
            }

            $scan = $this->fileManager->scanDir($templatePath);
            if (!empty($scan)) {
                foreach ($scan as $dir) {
                    if ($dir == '.' || $dir == '..') {
                        continue;
                    }
                    $templates[] = [
                      'name'=>ucfirst($dir),
                      'targetDir'=>$dir,
                      'version'=>false,
                    ];
                }
            }

            return $templates;
        }

        try {
            $executeArtisan = $this->shellExecutor->executeCommand([
                'php',
                '-d memory_limit=512M',
                $this->path . '/artisan',
                'microweber:get-templates',
            ]);

            $decodeArtisanOutput = json_decode($executeArtisan, true);
            if (!empty($decodeArtisanOutput)) {
                return $decodeArtisanOutput;
            }
        } catch (\Exception $e) {

        }

        return [];
    }

    /**
     * @return array
     */
    public function getSupportedLanguages()
    {
        if (!$this->isInstalled()) {
            $langDir = $this->path . '/src/MicroweberPackages/Translation/resources/lang';
            if (!$this->fileManager->isDir($langDir)) {
                return [];
            }
            $languages = [
                'en_US'=>'EN_US',
                'bg_BG'=>'BG_BG',
                'ar_SA'=>'AR_SA'
            ];
            $scan = $this->fileManager->scanDir($langDir);
            if (!empty($scan)) {
                foreach ($scan as $dir) {
                    if ($dir == '.' || $dir == '..') {
                        continue;
                    }
                    $languageAbr = str_replace('.json', false, $dir);
                    $upperText = strtoupper($languageAbr);
                    $languages[trim($languageAbr)] = $upperText;
                }
            }

            return $languages;
        }

        try {
            $executeArtisan = $this->shellExecutor->executeCommand([
                'php',
                '-d memory_limit=512M',
                $this->path . '/artisan',
                'microweber:get-languages',
            ]);

            $decodeArtisanOutput = json_decode($executeArtisan, true);
            if (!empty($decodeArtisanOutput)) {
                return $decodeArtisanOutput;
            }
        } catch (\Exception $e) {

        }

        return [];
    }

    /**
     * @return false|string
     */
    public function enableAdminLoginWithToken()
    {
        try {
              $executeArtisan = $this->shellExecutor->executeCommand([
                  'php',
                  '-d memory_limit=512M',
                  $this->path . '/artisan',
                  'microweber:module',
                  'login-with-token',
                  '1',
              ]);

            return $executeArtisan;

        } catch (\Exception $e) {

        }

        return false;
    }

    public function getAppDetails()
    {
        try {
            $executeArtisan = $this->shellExecutor->executeCommand([
                'php',
                '-d memory_limit=512M',
                $this->path . '/artisan',
                'microweber:get-app-details',
            ]);
            $decodeArtisanOutput = json_decode($executeArtisan, true);
            if (!empty($decodeArtisanOutput)) {
                return $decodeArtisanOutput;
            }
        } catch (\Exception $e) {

        }
        return false;
    }

    /**
     * @return false|string
     */
    public function generateAdminLoginToken()
    {
        try {
            $token = $this->shellExecutor->executeCommand([
                'php',
                '-d memory_limit=512M',
                $this->path . '/artisan',
                'microweber:generate-admin-login-token'
            ]);

            return $token;

        } catch (\Exception $e) {
            // error
        }

        return false;
    }

}
