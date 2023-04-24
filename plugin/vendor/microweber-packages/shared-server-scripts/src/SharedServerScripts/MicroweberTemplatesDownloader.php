<?php
namespace MicroweberPackages\SharedServerScripts;

use MicroweberPackages\ComposerClient\Client;
use MicroweberPackages\SharedServerScripts\FileManager\Adapters\NativeFileManager;
use MicroweberPackages\SharedServerScripts\Shell\Adapters\NativeShellExecutor;
use MicroweberPackages\SharedServerScripts\Shell\ShellExecutor;

class MicroweberTemplatesDownloader {

    /**
     * @var NativeFileManager
     */
    public $fileManager;

    /**
     * @var NativeShellExecutor
     */
    public $shellExecutor;

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
        $this->realeaseSource = $source;
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

        $templates = $this->_getTemplatesFromComposer();
        if (empty($templates)) {
            throw new \Exception('No templates found from composer client.');
        }

        $downloaded = [];
        foreach ($templates as $template) {
            $downloadToPath = $target . DIRECTORY_SEPARATOR . $template['target-dir'] . DIRECTORY_SEPARATOR;
            $downloaded[] = $this->downloadTemplate($template['dist']['url'], $downloadToPath);
        }

        if (!empty($downloaded)) {
            return $downloaded;
        }

        throw new \Exception('Something went wrong when downloading the app templates.');
    }

    /**
     * @param $url
     * @param $target
     * @return string
     */
    public function downloadTemplate($url, $target)
    {
        $status = $this->shellExecutor->executeFile(dirname(dirname(__DIR__))
            . DIRECTORY_SEPARATOR . 'shell-scripts'
            . DIRECTORY_SEPARATOR . 'unzip_app_template.sh', [base64_encode($url), $target]);

        return $status;
    }

    /**
     * @return array
     */
    public function _getTemplatesFromComposer()
    {
        $templates = [];
        foreach ($this->composerClient->search() as $packageName=>$packageVersions) {
            foreach ($packageVersions as $packageVersion) {
                if ($packageVersion['type'] !== 'microweber-template') {
                    continue;
                }
                if ($packageVersion['dist']['type'] == 'license_key') {
                    continue;
                }
                $templates[$packageName] = $packageVersion;
            }
        }

        return $templates;
    }

}
