<?php

namespace MicroweberPackages\SharedServerScripts;

use MicroweberPackages\SharedServerScripts\FileManager\Adapters\NativeFileManager;

class MicroweberUninstaller {

    /**
     * @var
     */
    public $path;

    /**
     * @var NativeFileManager
     */
    public $fileManager;

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
     * @param $adapter
     * @return void
     */
    public function setFileManager($adapter)
    {
        $this->fileManager = $adapter;
    }


    public function run()
    {
        $deletedFiles = [];

        $files = $this->_getFilesForDelete();

        foreach ($files as $file) {
            $deleteFile = $this->path . DIRECTORY_SEPARATOR . $file;
            if ($this->fileManager->isFile($deleteFile)) {
                $this->fileManager->unlink($deleteFile);
                $deletedFiles[] = $file;
            }
        }

        $dirs = $this->_getDirsForDelete();
        foreach ($dirs as $dir) {
            $deleteDir = $this->path . DIRECTORY_SEPARATOR . $dir;
            if ($this->fileManager->isDir($deleteDir)) {
                $this->fileManager->rmdirRecursive($deleteDir);
                $deletedFiles[] = $file;
            }
        }

        return ['done'=>true,'deleted'=>$deletedFiles];
    }


    private function _getDirsForDelete() {

        $dirs = [];
        $dirs[] = 'bootstrap';
        $dirs[] = 'vendor';
        $dirs[] = 'config';
        $dirs[] = 'database';
        $dirs[] = 'resources';
        $dirs[] = 'src';
        $dirs[] = 'storage';
        $dirs[] = 'userfiles';

        return $dirs;
    }

    private function _getFilesForDelete() {

        $files = [];
        $files[] = 'version.txt';
        $files[] = 'phpunit.xml';
        $files[] = 'index.php';
        $files[] = '.htaccess';
        $files[] = 'favicon.ico';
        $files[] = 'composer.json';
        $files[] = 'artisan';

        return $files;
    }

}
