<?php

namespace MicroweberPackages\SharedServerScripts;

use MicroweberPackages\SharedServerScripts\FileManager\Adapters\NativeFileManager;
use MicroweberPackages\SharedServerScripts\FileManager\Adapters\PleskServerFileManager;
use MicroweberPackages\SharedServerScripts\Shell\Adapters\NativeShellExecutor;
use MicroweberPackages\SharedServerScripts\Shell\Adapters\PleskShellExecutor;
use PHPUnit\Framework\TestCase;

class MicroweberDownloaderTest extends TestCase
{
    public function testDownload()
    {

        $temp = dirname(__DIR__).'/temp';
        if (!is_dir($temp)) {
            mkdir($temp);
        }

        $downloadTargetPath = $temp .'/microweber-latest/';

        $downloader = new MicroweberDownloader();
        $downloader->setFileManager(new NativeFileManager());
        $downloader->setShellExecutor(new NativeShellExecutor());

        $status = $downloader->download($downloadTargetPath);

        $this->assertTrue(is_dir($downloadTargetPath));
        $this->assertTrue(is_dir($downloadTargetPath.'/vendor'));
        $this->assertTrue(is_dir($downloadTargetPath.'/userfiles'));
        $this->assertTrue(is_file($downloadTargetPath.'/index.php'));
    }

}
