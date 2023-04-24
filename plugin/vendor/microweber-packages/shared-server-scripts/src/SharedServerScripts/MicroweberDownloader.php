<?php
namespace MicroweberPackages\SharedServerScripts;

use MicroweberPackages\ComposerClient\Client;
use MicroweberPackages\SharedServerScripts\FileManager\Adapters\NativeFileManager;
use MicroweberPackages\SharedServerScripts\Interfaces\IMicroweberDownloader;
use MicroweberPackages\SharedServerScripts\Shell\Adapters\NativeShellExecutor;
use MicroweberPackages\SharedServerScripts\Shell\ShellExecutor;

class MicroweberDownloader implements IMicroweberDownloader {

    /**
     * @var NativeFileManager
     */
    public $fileManager;

    /**
     * @var NativeShellExecutor
     */
    public $shellExecutor;

    const DEV_RELEASE = 'dev';
    const STABLE_RELEASE = 'stable';

    /**
     * @var string
     */
    public $realeaseSource = self::STABLE_RELEASE;

    /**
     * @var Client
     */
    public $composerClient;

    /**
     * @param $fileManagerAdapter
     * @param $shellExecutorAdapter
     */
    public function __construct() {
        $this->fileManager = new NativeFileManager();
        $this->shellExecutor = new NativeShellExecutor();
        $this->composerClient = new Client();
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
     * @param $adapter
     * @return void
     */
    public function setShellExecutor($adapter)
    {
        $this->shellExecutor = $adapter;
    }

    /**
     * @param $client
     * @return void
     */
    public function setComposerClient($client)
    {
        $this->composerClient = $client;
    }

    /**
     * @param $source
     * @return void
     */
    public function setReleaseSource($source)
    {
        if ($source == self::DEV_RELEASE) {
            return $this->realeaseSource = $source;
        }

        if ($source == self::STABLE_RELEASE) {
            return $this->realeaseSource = $source;
        }

        throw new \Exception('Please, use constants');
    }

    /**
     * @param string $target
     * @return void
     */
    public function download(string $target)
    {
        // Validate target path
        if (!$this->fileManager->isDir(dirname($target))) {
            throw new \Exception('Parent folder of target path is not valid.' . dirname($target));
        }

        if (!$this->fileManager->isWritable(dirname($target))) {
            throw new \Exception('Parent folder of target path is not writable.');
        }

        // Get latest release of app
        $release = $this->getRelease();
        if (empty($release)) {
            throw new \Exception('No releases found.');
        }

        // Download the app
        $status = $this->downloadMainApp($release['url'], $target);

        // Validate app installation
        $mainAppDownloadingErrors = [];
        if (!$this->fileManager->isDir($target)) {
            $mainAppDownloadingErrors[] = true;
        }
        if (!$this->fileManager->isFile($target . DIRECTORY_SEPARATOR . 'index.php')) {
            $mainAppDownloadingErrors[] = true;
        }

        if (!empty($mainAppDownloadingErrors)) {
            throw new \Exception('Error when downloading the main app. Reason: ' . $status);
        }

        if (strpos($status, 'Done') !== false) {
            return ['downloaded'=>true];
        }

        throw new \Exception('Something went wrong when downloading the main app. Reason: ' . $status);
    }

    /**
     * @param $url
     * @param $target
     * @return string
     */
    public function downloadMainApp($url, $target)
    {
        $status = $this->shellExecutor->executeFile(dirname(dirname(__DIR__))
            . DIRECTORY_SEPARATOR . 'shell-scripts'
            . DIRECTORY_SEPARATOR . 'unzip_app_version.sh', [base64_encode($url), $target]);

        return $status;
    }

    public function getVersion()
    {
        $release = $this->getRelease();

        $releaseVersion = '--';

        if (isset($release['version_url'])) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $release['version_url']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $releaseVersion = curl_exec($ch);
        }

        return $releaseVersion;
    }

    /**
     * @return string[]
     */
    public function getRelease()
    {
        if ($this->realeaseSource == self::DEV_RELEASE) {
            $branch = 'dev';
            return [
                'version'=>'Latest development version',
                'composer_url'=>'http://updater.microweberapi.com/builds/'.$branch.'/composer.json',
                'version_url'=>'http://updater.microweberapi.com/builds/'.$branch.'/version.txt',
                'url'=>'http://updater.microweberapi.com/builds/'.$branch.'/microweber.zip'
            ];
        }

        return [
            'version'=>'Latest production version',
            'composer_url'=>'http://updater.microweberapi.com/builds/master/composer.json',
            'version_url'=>'http://updater.microweberapi.com/builds/master/version.txt',
            'url'=>'http://updater.microweberapi.com/builds/master/microweber.zip'
        ];
    }

}
