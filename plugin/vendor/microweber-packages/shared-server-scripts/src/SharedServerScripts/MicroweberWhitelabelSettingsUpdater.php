<?php
namespace MicroweberPackages\SharedServerScripts;

use MicroweberPackages\SharedServerScripts\FileManager\Adapters\NativeFileManager;
use MicroweberPackages\SharedServerScripts\Shell\Adapters\NativeShellExecutor;

class MicroweberWhitelabelSettingsUpdater {

    /**
     * @var
     */
    public $path;

    /**
     * @param $adapter
     * @return void
     */
    public function setFileManager($adapter)
    {
        $this->fileManager = $adapter;
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
     * @param $path
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }


    public function __construct() {
        $this->fileManager = new NativeFileManager();
        $this->shellExecutor = new NativeShellExecutor();
    }

    public function apply($settings)
    {
        $whitelabelJson = json_encode($settings, JSON_PRETTY_PRINT);

        $whmFilePath = $this->path . '/storage/';
        $whmFileName = 'branding.json';

        if (!$this->fileManager->isDir($whmFilePath)) {
            $this->fileManager->mkdir($whmFilePath, null, true);
        }

        return $this->fileManager->filePutContents($whmFilePath . $whmFileName, $whitelabelJson);
    }

}
