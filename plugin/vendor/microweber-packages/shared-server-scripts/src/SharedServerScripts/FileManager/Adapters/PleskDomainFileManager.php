<?php
namespace MicroweberPackages\SharedServerScripts\FileManager\Adapters;

class PleskDomainFileManager implements IFileManager
{
    /**
     * @var \pm_FileManager
     */
    public $fileManager;

    /**
     * @var
     */
    public $domainId;


    /**
     * @param $domainId
     * @return void
     */
    public function setDomainId($domainId)
    {
        $this->domainId = $domainId;
        $this->fileManager = new \pm_FileManager($this->domainId);
    }

    /**
     * @param $dir
     * @return mixed
     */
    public function isDir($dir)
    {
        return $this->fileManager->isDir($dir);
    }

    /**
     * @param $file
     * @return mixed
     */
    public function fileExists($file)
    {
        return $this->fileManager->fileExists($file);
    }

    /**
     * @param $file
     * @return mixed
     */
    public function fileExtension($file)
    {
        $file = rtrim($file, '.');
        return substr($file, strrpos($file, '.') + 1);
    }

    /**
     * @param $from
     * @param $to
     * @return mixed
     */
    public function copy($from, $to)
    {
        return $this->fileManager->copyFile($from, $to);
    }

    /**
     * @param $dir
     * @return mixed
     */
    public function isWritable($dir)
    {
        return $this->fileManager->isWritable($dir);
    }

    /**
     * @param $dir
     * @return mixed
     */
    public function scanDir($dir) {
        return $this->fileManager->scanDir($dir);
    }
}
