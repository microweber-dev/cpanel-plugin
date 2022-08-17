<?php
namespace MicroweberPackages\SharedServerScripts\FileManager\Adapters;

class PleskServerFileManager implements IFileManager
{
    /**
     * @var \pm_FileManager
     */
    public $fileManager;

    /**
     * @var
     */
    public $domainId;


    public function __construct()
    {
        $this->fileManager = new \pm_ServerFileManager($this->domainId);
    }

    /**
     * @param $domainId
     * @return void
     */
    public function setDomainId($domainId)
    {
        $this->domainId = $domainId;
        $this->fileManager = new \pm_ServerFileManager($this->domainId);
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
     * @param $dir
     * @return mixed
     */
    public function isWritable($dir)
    {
        return $this->fileManager->isWritable($dir);
    }

}
