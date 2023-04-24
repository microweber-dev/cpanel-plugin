<?php
namespace MicroweberPackages\SharedServerScripts;

use MicroweberPackages\SharedServerScripts\FileManager\Adapters\NativeFileManager;

class MicroweberReinstaller extends MicroweberInstaller {

    public function run()
    {
        if ($this->type == self::TYPE_SYMLINK) {
            return $this->runSymlinkReinstall();
        }

        if ($this->type == self::TYPE_STANDALONE) {
            return $this->runStandaloneReinstall();
        }
    }


    public function runSymlinkReinstall()
    {
        $this->enableChownAfterInstall();

        foreach ($this->_getFilesForSymlinking() as $fileOrFolder) {

            $sourceDirOrFile = $this->sourcePath . '/' . $fileOrFolder;
            $targetDirOrFile = $this->path . '/' . $fileOrFolder;

            // Skip symlinked file
            if ($this->fileManager->isLink($targetDirOrFile)) {
                continue;
            }

            if ($this->fileManager->isDir($targetDirOrFile)) {
                $this->fileManager->rmdirRecursive($targetDirOrFile);
            }

            if ($this->fileManager->isFile($targetDirOrFile)) {
                $this->fileManager->unlink($targetDirOrFile);
            }

            // Create symlink
            $this->fileManager->symlink($sourceDirOrFile, $targetDirOrFile);
        }

        $this->addMissingConfigFiles();

        $this->_chownFolders();
    }

    public function runStandaloneReinstall()
    {
        $this->enableChownAfterInstall();

        foreach ($this->_getFilesForSymlinking() as $fileOrFolder) {

            $sourceDirOrFile = $this->sourcePath . '/' . $fileOrFolder;
            $targetDirOrFile = $this->path . '/' . $fileOrFolder;

            // Delete symlink
            if ($this->fileManager->isLink($targetDirOrFile)) {
                $this->fileManager->unlink($targetDirOrFile);
            }

            // Delete file
            if ($this->fileManager->isFile($targetDirOrFile)) {
                $this->fileManager->unlink($targetDirOrFile);
            }

            // Delete folder
            if ($this->fileManager->isDir($sourceDirOrFile)) {
                if ($this->fileManager->isDir($targetDirOrFile)) {
                    $this->fileManager->rmdirRecursive($targetDirOrFile);
                }
                $this->fileManager->copyFolder($sourceDirOrFile, $targetDirOrFile);
            }

        }

        // And then we will copy files
        foreach ($this->_getFilesForCopy() as $file) {

            $sourceDirOrFile = $this->sourcePath .'/'. $file;
            $targetDirOrFile = $this->path .'/'. $file;

            if ($this->fileManager->isFile($targetDirOrFile)) {
                unlink($targetDirOrFile);
            }

            $this->fileManager->copy($sourceDirOrFile, $targetDirOrFile);
        }

        $this->addMissingConfigFiles();

        $this->_chownFolders();
    }

    public function addMissingConfigFiles()
    {
        $sourceDirOfConfigs = [];
        $sourceDirOfConfigsList = $this->fileManager->scanDir($this->sourcePath . '/config');
        if (!empty($sourceDirOfConfigsList)) {
            foreach ($sourceDirOfConfigsList as $configFile) {
                if ($configFile == '.' || $configFile == '..') {
                    continue;
                }
                $configFileExt = $this->fileManager->fileExtension($this->sourcePath . '/config/'.$configFile);
                if ($configFileExt !== 'php') {
                    continue;
                }

                $sourceDirOfConfigs[] = $configFile;
            }
        }

        $targetDirOfConfigs = [];
        $targetDirOfConfigsList = $this->fileManager->scanDir($this->path . '/config');
        if (!empty($targetDirOfConfigsList)) {
            foreach ($targetDirOfConfigsList as $targetConfigFile) {
                if ($targetConfigFile == '.' || $targetConfigFile == '..') {
                    continue;
                }
                $targetConfigFileExt = $this->fileManager->fileExtension($this->path . '/config/'.$targetConfigFile);
                if ($targetConfigFileExt !== 'php') {
                    continue;
                }
                $targetDirOfConfigs[] = $targetConfigFile;
            }
        }

        $missingConfigs = [];
        foreach ($sourceDirOfConfigs as $sourceConfig) {
            if (!in_array($sourceConfig, $targetDirOfConfigs)) {
                $missingConfigs[] = $sourceConfig;
            }
        }

        if (!empty($missingConfigs)) {
            foreach ($missingConfigs as $missingConfig) {

                $sourceConfigFile = $this->sourcePath . '/config/'.$missingConfig;
                $targetConfigFile = $this->path .'/config/' . $missingConfig;

                if (!$this->fileManager->fileExists($targetConfigFile)) {
                    $this->fileManager->copy($sourceConfigFile, $targetConfigFile);
                }
            }
        }

    }

}
