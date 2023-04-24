<?php

namespace MicroweberPackages\SharedServerScripts;

use MicroweberPackages\SharedServerScripts\FileManager\Adapters\NativeFileManager;
use MicroweberPackages\SharedServerScripts\Shell\Adapters\NativeShellExecutor;

class MicroweberInstaller {

    public const TYPE_STANDALONE = 'standalone';
    public const TYPE_SYMLINK = 'symlink';

    public const DATABASE_DRIVER_MYSQL = 'mysql';
    public const DATABASE_DRIVER_SQLITE = 'sqlite';

    /**
     * @var string
     */
    protected $type = self::TYPE_STANDALONE;

    /**
     * @var string
     */
    protected $databaseDriver = self::DATABASE_DRIVER_SQLITE;

    /**
     * @var string
     */
    protected $databaseHost = 'localhost:3306';

    /**
     * @var
     */
    protected $databaseUsername;

    /**
     * @var
     */
    protected $databasePassword;

    /**
     * @var
     */
    protected $databaseName;

    /**
     * @var string
     */
    protected $adminEmail = 'admin@microweber.com';

    /**
     * @var string
     */
    protected $adminUsername = '';

    /**
     * @var string
     */
    protected $adminPassword = '';

    /**
     * @var bool
     */
    protected $path = false;

    /**
     * @var bool
     */
    protected $sourcePath = false;

    /**
     * @var bool
     */
    protected $chownUser = false;

    /**
     * @var bool
     */
    protected $chownAfterInstall = false;

    /**
     * @var bool
     */
    protected $logger = false;

    /**
     * @var string
     */
    protected $template = 'new-world';

    /**
     * @var string
     */
    protected $language = 'en';

    /**
     * @param $logger
     * @return void
     */
    public function setLogger($logger) {
        $this->logger = $logger;
    }

    /**
     * @param $path
     * @return void
     */
    public function setPath($path) {
        $this->path = $path;
    }

    /**
     * @param $path
     * @return void
     */
    public function setSourcePath($path) {
        $this->sourcePath = $path;
    }


    public function setSymlinkInstallation() {
        $this->type = self::TYPE_SYMLINK;
    }

    public function setStandaloneInstallation() {
        $this->type = self::TYPE_STANDALONE;
    }

    /**
     * @param $language
     * @return void
     */
    public function setLanguage($language) {
        $this->language = $language;
    }

    /**
     * @param $template
     * @return void
     */
    public function setTemplate($template) {
        $this->template = $template;
    }

    /**
     * @param $driver
     * @return void
     */
    public function setDatabaseDriver($driver) {
        $this->databaseDriver = $driver;
    }

    /**
     * @param $username
     * @return void
     */
    public function setDatabaseUsername($username) {
        $this->databaseUsername = $username;
    }

    /**
     * @param $password
     * @return void
     */
    public function setDatabasePassword($password) {
        $this->databasePassword = $password;
    }

    /**
     * @param $name
     * @return void
     */
    public function setDatabaseName($name) {
        $this->databaseName = $name;
    }

    /**
     * @param $email
     * @return void
     */
    public function setAdminEmail($email) {
        $this->adminEmail = $email;
    }

    /**
     * @param $username
     * @return void
     */
    public function setAdminUsername($username) {
        $this->adminUsername = $username;
    }

    /**
     * @param $password
     * @return void
     */
    public function setAdminPassword($password) {
        $this->adminPassword = $password;
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
     * @param $user
     * @return void
     */
    public function setChownUser($user)
    {
        $this->chownUser = $user;
    }

    public function getChownUser()
    {
        if ($this->chownUser) {
            return $this->chownUser;
        }

        return $this->_getFileOwnership($this->path);
    }

    /**
     * @return void
     */
    public function enableChownAfterInstall()
    {
        $this->chownAfterInstall = true;
    }

    public function __construct() {
        $this->fileManager = new NativeFileManager();
        $this->shellExecutor = new NativeShellExecutor();
    }

    public function run() {

        if (!$this->fileManager->isDir($this->path)) {
            $this->fileManager->mkdir($this->path);
        }

        // Clear domain files if exists
        $this->_prepairPathFolder();

        // First we will make a directories
        foreach ($this->_getDirsToMake() as $dir) {
            $this->fileManager->mkdir($this->path . '/' . $dir, '0755', true);
        }

        foreach ($this->_getFilesForSymlinking() as $fileOrFolder) {

            $sourceDirOrFile = $this->sourcePath . '/' . $fileOrFolder;
            $targetDirOrFile = $this->path . '/' . $fileOrFolder;

            if (!$this->fileManager->fileExists($sourceDirOrFile)) {
                continue;
            }

            if ($this->type == self::TYPE_SYMLINK) {
                // Create symlink
                $this->fileManager->symlink($sourceDirOrFile, $targetDirOrFile);
            } else {
                if ($this->fileManager->isDir($sourceDirOrFile)) {
                    $this->fileManager->copyFolder($sourceDirOrFile, $targetDirOrFile);
                } else {
                    $this->fileManager->copy($sourceDirOrFile, $targetDirOrFile);
                }
            }
        }


        // And then we will copy folders
        foreach ($this->_getDirsToCopy() as $folder) {
            $sourceDir = $this->sourcePath .'/'. $folder;
            $targetDir = $this->path .'/'. $folder;
            $this->fileManager->copyFolder($sourceDir, $targetDir);
        }

        // And then we will copy files
        foreach ($this->_getFilesForCopy() as $file) {
            $sourceFile = $this->sourcePath .'/'. $file;
            $targetFile = $this->path .'/'. $file;
            $this->fileManager->copy($sourceFile, $targetFile);
        }

        if ($this->type == self::TYPE_SYMLINK) {
            $this->_fixHtaccess();
        }

        if ($this->databaseDriver == self::DATABASE_DRIVER_SQLITE) {
            $this->databaseHost = 'localhost';
            $this->databaseName = $this->path . '/storage/database.sqlite';
        }

        $installArguments = [];

        // Admin details
        $installArguments[] =  '--email='.  $this->adminEmail;
        $installArguments[] =  '--username='.  $this->adminUsername;
        $installArguments[] =  '--password='.  $this->adminPassword;

        // Database settings
        $installArguments[] = '--db-host='.  $this->databaseHost;
        $installArguments[] = '--db-name='.  $this->databaseName;
        $installArguments[] = '--db-username='.  $this->databaseUsername;
        $installArguments[] = '--db-password='.  $this->databasePassword;
        $installArguments[] = '--db-driver='.  $this->databaseDriver;

        if ($this->language) {
            $installArguments[] = '--language='.  trim($this->language);
        }

        $installArguments[] = '--db-prefix=site_';

        if ($this->template) {
            $installArguments[] = '--template='. $this->template;
        }

        $installArguments[] = '--default-content=1';

        if (!$this->template) {
            $installArguments[] = '--config-only=1';
        }

        try {

            $this->_chownFolders();

            $artisanCommand = array_merge([
                'php',
                '-d memory_limit=512M',
                $this->path . '/artisan',
                'microweber:install',
            ], $installArguments);

            $executeArtisan = $this->shellExecutor->executeCommand($artisanCommand, $this->path, [
                'APP_ENV' => false,
                'DB_CONNECTION' => false,
                'APP_KEY' => false,
                'SYMFONY_DOTENV_VARS' => false,
            ]);

            $this->_chownFolders();

            $success = false;
            if (strpos($executeArtisan, 'done') !== false) {
                $success = true;
            }

            return ['success'=>$success, 'log'=> $executeArtisan];
        } catch (Exception $e) {
            return ['success'=>false, 'error'=>true, 'log'=> $e->getMessage()];
        }

    }

    public function _chownFolders()
    {
        if ($this->chownAfterInstall) {

            $chownUser = $this->getChownUser();

            $status = $this->shellExecutor->executeFile(dirname(dirname(__DIR__))
                . DIRECTORY_SEPARATOR . 'shell-scripts'
                . DIRECTORY_SEPARATOR . 'chown_installed_app.sh', [$chownUser, $this->path]);

        }
    }

    private function _fixHtaccess()
    {
        try {
            $content = $this->fileManager->fileGetContents($this->path . '/.htaccess');
            $content = str_replace('-MultiViews -Indexes', 'FollowSymLinks', $content);

            $this->fileManager->filePutContents($this->path . '/.htaccess', $content);

        } catch (Exception $e) {
            // Error
        }
    }

    private function _prepairPathFolder()
    {
        try {
            $findedFiles = [];
            foreach ($this->fileManager->scanDir($this->path) as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                $findedFiles[] = $file;
            }

            if (!empty($findedFiles)) {

                // Make backup dir
                $backupMainFilesPath = $this->path . '/backup_files/';
                if (!$this->fileManager->isDir($backupMainFilesPath)) {
                    $this->fileManager->mkdir($backupMainFilesPath);
                }
                $backupFilesPath = $backupMainFilesPath . 'backup-' . date('Y-m-d-H-i-s');
                if (!$this->fileManager->isDir($backupFilesPath)) {
                    $this->fileManager->mkdir($backupFilesPath);
                }

                // Move files to backup dir
                foreach ($findedFiles as $file) {
                    if ($file == 'backup_files') {
                        continue;
                    }
                    $this->fileManager->moveFile($this->path . '/' . $file, $backupFilesPath . '/' . $file);
                }

            }

        } catch (Exception $e) {
            // error
        }

    }

    public function _getDirsToMake() {

        $dirs = [];

        // Storage dirs
        $dirs[] = 'storage';
        $dirs[] = 'storage/framework';
        $dirs[] = 'storage/framework/sessions';
        $dirs[] = 'storage/framework/views';
        $dirs[] = 'storage/cache';
        $dirs[] = 'storage/logs';
        $dirs[] = 'storage/app';

        // Bootstrap dirs
        $dirs[] = 'bootstrap';
        $dirs[] = 'bootstrap/cache';

        // User files dirs
        $dirs[] = 'userfiles';
        $dirs[] = 'userfiles/media';
        $dirs[] = 'userfiles/modules';
        $dirs[] = 'userfiles/templates';

        return $dirs;
    }

    public function _getDirsToCopy() {

        $dirs = [];

        // Config dir
        $dirs[] = 'config';

        return $dirs;
    }

    public function _getFilesForSymlinking() {

        $files = [];
        $files[] = 'vendor';
        $files[] = 'src';
        $files[] = 'resources';
        $files[] = 'database';
        $files[] = 'userfiles/elements';
        $files[] = 'storage/branding.json';
        $files[] = 'version.txt';

        $listTemplates = $this->fileManager->scanDir($this->sourcePath . '/userfiles/templates');
        if (!empty($listTemplates)) {
            foreach ($listTemplates as $template) {
                if ($template == '.' || $template == '..') {
                    continue;
                }
                $files[] = 'userfiles/templates/' . $template;
            }
        }

        $listModules = $this->fileManager->scanDir($this->sourcePath . '/userfiles/modules');
        if (!empty($listModules)) {
            foreach ($listModules as $module) {
                if ($module == '.' || $module == '..') {
                    continue;
                }
                $files[] = 'userfiles/modules/' . $module;
            }
        }

        return $files;
    }

    /**
     * This is the files when symlinking app.
     * @return string[]
     */
    public function _getFilesForCopy() {

        $files = [];

        // Index
        $files[] = 'phpunit.xml';
        $files[] = 'index.php';
        $files[] = '.htaccess';
        $files[] = 'favicon.ico';
        $files[] = 'composer.json';
        $files[] = 'artisan';

        // Bootstrap folder
        $files[] = 'bootstrap/.htaccess';
        $files[] = 'bootstrap/app.php';
        $files[] = 'bootstrap/autoload.php';

        return $files;
    }

    public static function getRandomPassword($length = 16, $complex = false)
    {
        $alphabet = 'ghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        if ($complex) {
            $alphabet_complex = '!@#$%^&*?_~';
        }

        $pass = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i ++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        if ($complex) {
            $alphaLength = strlen($alphabet_complex) - 1;
            for ($i = 0; $i < $length; $i ++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet_complex[$n];
            }

            shuffle($pass);
        }

        return implode($pass);
    }

    private function _getFileOwnership($file)
    {
        $stat = stat($file);
        if ($stat) {

            // $group = posix_getgrgid($stat[5]);
            $user = posix_getpwuid($stat[4]);

            if (isset($user['name'])) {
                return $user['name'];
            }
        }

    }
}
