<?php
namespace wapmorgan\rpm;

use PharData;
use DirectoryIterator;

class Packager {
    private $_spec;
    private $mountPoints = array();
    private $outputPath;

    /**
     * Set the control file
     *
     * This file contains the base package information
     *
     * @param Spec $spec
     * @return \wapmorgan\rpm\Spec
     */
    public function setSpec(Spec $spec)
    {
        $this->_spec = $spec;
        return $this;
    }

    /**
     * Return the actual spec file
     *
     * @return Spec
     */
    public function getSpec()
    {
        return $this->_spec;
    }

    public function setOutputPath($path) {
        $this->outputPath = $path;
        return $this;
    }

    public function getOutputPath() {
        return $this->outputPath;
    }

    public function addMount($sourcePath, $destinationPath) {
        $this->mountPoints[$sourcePath] = $destinationPath;
        return $this;
    }

    public function movePackage($dest) {
        return rename($_SERVER['HOME'].'/rpmbuild/RPMS/'.$this->_spec->BuildArch.'/'.$this->_spec->Name.'-'.$this->_spec->Version.'-'.$this->_spec->Release.'.'.$this->_spec->BuildArch.'.rpm', $dest);
    }

    public function run() {

        if (!is_dir($_SERVER['HOME'].'/rpmbuild/SOURCES'))
            mkdir_recursive($_SERVER['HOME'].'/rpmbuild/SOURCES', 0777);
        if (!is_dir($_SERVER['HOME'].'/rpmbuild/SPECS'))
            mkdir_recursive($_SERVER['HOME'].'/rpmbuild/SPECS', 0777);

        if (file_exists($this->getOutputPath())) {
            $iterator = new DirectoryIterator($this->getOutputPath());
            foreach ($iterator as $path) {
                if ($path != '.' && $path != '..') {
                    echo "OUTPUT DIRECTORY MUST BE EMPTY! Something exists, exit immediately!" . PHP_EOL;
                    exit();
                }
            }
        }

        if (!is_dir($this->getOutputPath()))
            mkdir_recursive($this->getOutputPath(), 0777);

        foreach ($this->mountPoints as $path => $dest) {
            $this->pathToPath($path, $this->getOutputPath().DIRECTORY_SEPARATOR.$dest);
        }

        $spec = $this->_spec;
        $spec->setPrep('%autosetup -c package');
        $install_section = 'rm -rf %{buildroot}'."\n".'mkdir -p %{buildroot}'."\n".'cp -rp * %{buildroot}';
        $spec->setInstall($install_section);

        $files_section = null;
        foreach ($this->mountPoints as $sourcePath => $destinationPath) {
            if (is_dir($sourcePath)) {
                $files_section .= (strlen($files_section) > 0 ? "\n" : null).rtrim($destinationPath, '/').'/';
            } else {
                $files_section .= (strlen($files_section) > 0 ? "\n" : null).'%attr('.substr(sprintf('%o', fileperms($sourcePath)), -4).',root,root) '.$destinationPath;
            }
        }

        $spec->setFiles($files_section);
        // $used_dirs = array();
        // foreach ($this->mountPoints as $source => $dest) {
        //     if (is_dir($source)) {
        //         $files[] = $dest;
        //     } else {
        //         $dir = dirname($dest);
        //         // directory exists on a real machine - add files one by one
        //         if (is_dir($dir)) {
        //             $files[] = $dest;
        //         } else { // add all files within directory
        //             if (!in_array($dir, $files)) {
        //                 $files[] = $dir;
        //             }
        //         }
        //     }
        // }
        // $spec->setFiles(implode("\n", $files));

        if (file_exists($_SERVER['HOME'].'/rpmbuild/SOURCES/'.$this->_spec->Name.'.tar')) {
            unlink($_SERVER['HOME'].'/rpmbuild/SOURCES/'.$this->_spec->Name.'.tar');
        }
        $tar = new PharData($_SERVER['HOME'].'/rpmbuild/SOURCES/'.$this->_spec->Name.'.tar');
        $tar->buildFromDirectory($this->outputPath);
        $spec->setKey('Source0', $this->_spec->Name.'.tar');

        file_put_contents($_SERVER['HOME'].'/rpmbuild/SPECS/'.$this->_spec->Name.'.spec', (string)$this->_spec);

        return $this;
    }

    public function build() {
        $command = 'rpmbuild -bb '.$_SERVER['HOME'].'/rpmbuild/SPECS/'.$this->_spec->Name.'.spec';
        return $command;
    }

    private function pathToPath($path, $dest) {
        if (is_dir($path)) {
            $iterator = new DirectoryIterator($path);
            foreach ($iterator as $element) {
                if ($element != '.' && $element != '..') {
                    $fullPath = $path . DIRECTORY_SEPARATOR . $element;
                    if (is_dir($fullPath)) {
                        $this->pathToPath($fullPath, $dest . DIRECTORY_SEPARATOR . $element);
                    } else {
                        $this->copy($fullPath, $dest . DIRECTORY_SEPARATOR . $element);
                    }
                }
            }
        } else if (is_file($path)) {
            $this->copy($path, $dest);
        }
    }

    private function copy($source, $dest) {
        $destFolder = dirname($dest);
        if (!file_exists($destFolder)) {
            mkdir_recursive($destFolder, 0777, true);
        }
        copy($source, $dest);
        if (fileperms($source) != fileperms($dest))
            chmod($dest, fileperms($source));
    }
}