<?php

namespace MicroweberPackages\SharedServerScripts;

use MicroweberPackages\SharedServerScripts\FileManager\Adapters\NativeFileManager;

class MicroweberInstallationsScanner
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
     * @param $fileManagerAdapter
     */
    public function __construct()
    {
        $this->fileManager = new NativeFileManager();
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
     * @return array
     */
    public function scanRecusrive()
    {
        $installations = [];

        $scanedFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->path));
        foreach ($scanedFiles as $file) {

            if (!$file->isFile()) {
                continue;
            }

            $skip = true;
            if (strpos($file->getPathname(), '/config/microweber.php') !== false) {
                $skip = false;
            }

            if (strpos($file->getPathname(), '/backup_files/') !== false) {
                $skip = true;
            }

            if ($skip) {
                continue;
            }

            $scanPath = dirname(dirname($file->getPathname()));

            $installation = $this->scanPath($scanPath);

            if (!empty($installation)) {
                $installations[] = $installation;
            }

        }

        return $installations;
    }


    public function scanPath($path)
    {
        $sharedPathHelper = new MicroweberAppPathHelper();
        $sharedPathHelper->setPath($path);
        $createdAt = $sharedPathHelper->getCreatedAt();

        if (!$createdAt) {
            return;
        }

        return [
            'path'=>$path,
            'is_symlink'=>$sharedPathHelper->isSymlink(),
            'version'=>$sharedPathHelper->getCurrentVersion(),
            'app_details'=>$sharedPathHelper->getAppDetails(),
            'installed'=>$sharedPathHelper->isInstalled(),
            'supported_modules'=>$sharedPathHelper->getSupportedModules(),
            'supported_templates'=>$sharedPathHelper->getSupportedTemplates(),
            'supported_languages'=>$sharedPathHelper->getSupportedLanguages(),
            'created_at'=>$createdAt
        ];
    }

}
