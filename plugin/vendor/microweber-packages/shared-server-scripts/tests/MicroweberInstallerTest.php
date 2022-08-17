<?php

namespace MicroweberPackages\SharedServerScripts;

use MicroweberPackages\SharedServerScripts\FileManager\Adapters\PleskServerFileManager;
use MicroweberPackages\SharedServerScripts\Shell\Adapters\PleskShellExecutor;
use PHPUnit\Framework\TestCase;
use MicroweberPackages\SharedServerScripts\FileManager\Adapters\NativeFileManager;
use MicroweberPackages\SharedServerScripts\Shell\Adapters\NativeShellExecutor;

class MicroweberInstallerTest extends TestCase
{
    public function testInstall()
    {
        $temp = dirname(__DIR__).'/temp';
        if (!is_dir($temp)) {
            mkdir($temp);
        }

        $targetPath = dirname(__DIR__).'/temp/my-microweber-installation';
        $sourcePath = dirname(__DIR__).'/temp/microweber-latest';

        $installer = new MicroweberInstaller();
        $installer->setPath($targetPath);
        $installer->setSourcePath($sourcePath);
        $installer->setFileManager(new NativeFileManager());
        $installer->setShellExecutor(new NativeShellExecutor());
        $installer->setAdminUsername('bobi_unittest');
        $installer->setAdminPassword('unitest-pass');
        $installer->setAdminEmail('bobi@unitest.com');
        $installer->setSymlinkInstallation();

        $status = $installer->run();

        $this->assertTrue($status['success']);
        $this->assertTrue(is_file($targetPath.'/config/app.php'));
        $this->assertTrue(is_file($targetPath.'/config/microweber.php'));=

    }

    public function testReinstallFromSymlinkToStandalone()
    {
        $temp = dirname(__DIR__).'/temp';
        if (!is_dir($temp)) {
            mkdir($temp);
        }

        $targetPath = dirname(__DIR__).'/temp/my-microweber-installation';
        $sourcePath = dirname(__DIR__).'/temp/microweber-latest';

        $reInstaller = new MicroweberReinstaller();
        $reInstaller->setPath($targetPath);
        $reInstaller->setSourcePath($sourcePath);
        $reInstaller->setFileManager(new NativeFileManager());
        $reInstaller->setShellExecutor(new NativeShellExecutor());
        $reInstaller->setStandaloneInstallation();

        $status = $reInstaller->run();

        $this->assertTrue(is_dir($targetPath.'/vendor'));
        $this->assertTrue(is_dir($targetPath.'/userfiles'));
        $this->assertTrue(is_dir($targetPath.'/database'));
        $this->assertTrue(is_file($targetPath.'/config/app.php'));
        $this->assertTrue(is_file($targetPath.'/config/microweber.php'));
    }


    public function testReinstallFromStandaloneToSymlink()
    {
        $temp = dirname(__DIR__).'/temp';
        if (!is_dir($temp)) {
            mkdir($temp);
        }

        $targetPath = dirname(__DIR__).'/temp/my-microweber-installation';
        $sourcePath = dirname(__DIR__).'/temp/microweber-latest';

        $reInstaller = new MicroweberReinstaller();
        $reInstaller->setPath($targetPath);
        $reInstaller->setSourcePath($sourcePath);
        $reInstaller->setFileManager(new NativeFileManager());
        $reInstaller->setShellExecutor(new NativeShellExecutor());
        $reInstaller->setSymlinkInstallation();

        $status = $reInstaller->run();

        $this->assertTrue(is_link($targetPath.'/vendor'));
        $this->assertTrue(is_link($targetPath.'/userfiles'));
        $this->assertTrue(is_link($targetPath.'/database'));
        
        $this->assertTrue(is_file($targetPath.'/config/app.php'));
        $this->assertTrue(is_file($targetPath.'/config/microweber.php'));
    }
}
