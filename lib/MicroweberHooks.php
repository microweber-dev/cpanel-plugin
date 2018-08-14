<?php

include_once(__DIR__ . '/MicroweberStorage.php');
include_once(__DIR__ . '/MicroweberVersionsManager.php');
include_once(__DIR__ . '/MicroweberInstallCommand.php');
include_once(__DIR__ . '/MicroweberCpanelApi.php');

class MicroweberHooks
{
    private $input;
    private $storage;

    public function __construct($input = false)
    {
        $this->input = $input;
        $this->storage = new MicroweberStorage();
    }

    // Embed hook attribute information.
    public function describe()
    {
        $add_account = array(
            'category' => 'Whostmgr',
            'event' => 'Accounts::Create',
            'stage' => 'post',
            'hook' => '/var/cpanel/microweber/mw_hooks.php --add-account',
            'exectype' => 'script',
        );
        $remove_account = array(
            'category' => 'Whostmgr',
            'event' => 'Accounts::Remove',
            'stage' => 'post',
            'hook' => '/var/cpanel/microweber/mw_hooks.php --remove-account',
            'exectype' => 'script',
        );
        return json_encode(array($add_account, $remove_account));
    }

    public function remove_account()
    {
        $this->log('Removing account');
    }

    public function add_account()
    {
        $input = $this->input;
        $cpapi = new MicroweberCpanelApi();


        $domain = $input['data']['domain'];
        $installPath = $input['data']['homedir'];
        $adminEmail = $input['data']['contactemail'];
        $adminUsername = $input['data']['user'];
        $adminPassword = $input['data']['pass'];
        $source_path = '/usr/share/microweber/latest/';


        if (!$this->checkIfAutoInstall()) {
            $this->log('Website auto install is not enabled');
            return;

        }
        if (!$cpapi->checkIfFeatureEnabled($adminUsername)) {
            $this->log('Website feature is not enabled for user ' . $adminUsername);
            return;
        }

        $this->log('Adding website to account');


        $installPath = $installPath . '/public_html/';
        $this->install($domain, $source_path, $installPath, $adminEmail, $adminUsername, $adminPassword);
    }


    // ----------------------

    public function install($domain, $source_path, $installPath, $adminEmail, $adminUsername, $adminPassword, $dbHost = 'localhost', $dbDriver = 'mysql')
    {
        $cpapi = new MicroweberCpanelApi();

        $source_folder = $source_path;


        $version_manager = new MicroweberVersionsManager($source_folder);
        if (!$version_manager->hasDownloaded()) {
            $version_manager->download();
        }
        if (!$version_manager->hasDownloaded()) {
            $this->log('Error: Source cannot be downloaded in ' . $source_folder);
            return;
        }
        $this->log('Source is downloaded in ' . $source_folder);


        //$dbDriver @todo get from settings ?

        $dbNameLength = 15;
        $dbPrefix = $cpapi->makeDbPrefixFromUsername($adminUsername);
        $dbName = $dbPrefix . str_replace('.', '', $domain);
        $dbName = substr($dbName, 0, $dbNameLength);

        $dbUsername = $dbName;
        $dbPass = $cpapi->randomPassword(12);


        $this->log('Creating database user ' . $dbUsername);
        $cpapi->execUapi($adminUsername, 'Mysql', 'create_user', array('name' => $dbUsername, 'password' => $dbPass));


        $this->log('Creating database ' . $dbName);
        $cpapi->execUapi($adminUsername, 'Mysql', 'create_database', array('name' => $dbName));

        $this->log('Setting privileges ' . $dbUsername);
        $cpapi->execUapi($adminUsername, 'Mysql', 'set_privileges_on_database', array('user' => $dbUsername, 'database' => $dbName, 'privileges' => 'ALL PRIVILEGES'));


        $opts = array();
        $opts['user'] = $adminUsername;
        $opts['pass'] = $adminPassword;
        $opts['email'] = $adminEmail;
        $opts['database_driver'] = $dbDriver;
        $opts['database_user'] = $dbUsername;
        $opts['database_password'] = $dbPass;
        $opts['database_table_prefix'] = $dbPrefix;
        $opts['database_name'] = $dbName;
        $opts['source_folder'] = $source_folder;
        $opts['public_html_folder'] = $installPath;


        $opts['default_template'] = 'dream'; //@todo get from settings
        $opts['is_symliked'] = true; //@todo get from settings
        $opts['debug_email'] = 'boksiora@gmail.com'; //@todo get from settings


//        $install_opts = array();
//        $opts['options'] = $install_opts;

        $this->log('Running install command');

        $do_install = new MicroweberInstallCommand();
        $do_install = $do_install->install($opts);

        $this->log('Install command finished');

        return compact('adminEmail', 'adminUsername', 'adminPassword');
    }


    public function log($msg)
    {
        echo '[microweber]  ' . $msg . "\n";
    }

    private function OOOOLD_install($domain, $installPath, $adminEmail, $adminUsername, $adminPassword, $dbHost = 'localhost', $dbDriver = 'mysql')
    {

        $source_folder = '/usr/share/microweber/latest/';


        // Prepare data
        $zipInstallUrl = 'http://download.microweberapi.com/ready/core/microweber-latest.zip';
        $zipInstallPath = '/tmp/microweber-latest.zip';
        $zipUserfilesUrl = 'https://members.microweber.com/_partners/csigma/userfiles.zip';
        $zipUserfilesPath = '/tmp/userfiles.zip';


        $dbPrefix = substr($adminUsername, 0, 8) . '_';
        $dbNameLength = 16 - strlen($dbPrefix);
        $dbName = str_replace('.', '_', $domain);
        $dbName = $dbPrefix . substr($dbName, 0, $dbNameLength);
        $dbUsername = $dbName;
        $dbHost = $this->cpanel->uapi('Mysql', 'locate_server');
        $dbHost = $dbHost['cpanelresult']['result']['data']['remote_host'];
        $dbPass = $this->randomPassword();

        // Create database
        $this->execUapi('Mysql', 'create_database', array('name' => $dbName));
        $this->execUapi('Mysql', 'create_user', array('name' => $dbUsername, 'password' => $dbPass));
        $this->execUapi('Mysql', 'set_privileges_on_database', array('user' => $dbUsername, 'database' => $dbName, 'privileges' => 'ALL PRIVILEGES'));

        // Create empty install directory
        exec("rm -rf $installPath");
        mkdir($installPath);

        // Download install zip
        copy($zipInstallUrl, $zipInstallPath);
        exec("unzip $zipInstallPath -d $installPath");

        // Download userfiles zip
        copy($zipUserfilesUrl, $zipUserfilesPath);
        exec("unzip $zipUserfilesPath -d $installPath");

        // Permissions
        exec("chmod -R 755 $installPath");
        exec("chown $adminUsername $installPath");

        // Clear cache
        exec("php $installPath/artisan cache:clear");

        // $opts['user'];
        // $opts['pass'];
        // $opts['email'];
        // $opts['database_driver'];
        // $opts['database_name'];
        // $opts['database_user'];
        // $opts['database_password'];
        // $opts['database_table_prefix'];
        // $opts['default_template'];
        // $opts['source_folder'];
        // $opts['is_symliked'];
        // $opts['debug_email'];
        // $opts['debug_email_subject'];
        // $opts['install_debug_file'];
        // $opts['options'];
        // $opts['options'][0]['option_key'];


        $opts = array();
        $opts['user'] = $adminUsername;
        $opts['pass'] = $adminPassword;
        $opts['email'] = $adminEmail;
        $opts['database_driver'] = $dbDriver;
        $opts['database_user'] = $dbUsername;
        $opts['database_password'] = $dbPass;
        $opts['database_table_prefix'] = $dbPrefix;
        $opts['database_name'] = $dbName;
        $opts['default_template'] = 'dream';
        $opts['source_folder'] = $source_folder;

        $opts['is_symliked'] = true;
        $opts['debug_email'] = 'boksiora@gmail.com';

        $install_opts = array();
        $opts['options'] = $install_opts;


        $do_install = new MicroweberInstallCommand();
        $do_install = $do_install->install($opts);


        // Install Microweber
        $installCommand = "php $installPath/artisan microweber:install $adminEmail $adminUsername $adminPassword $dbHost $dbName $dbUsername $adminPassword $dbDriver -p $dbPrefix -t dream -d 1 -c 1";
        file_put_contents('/tmp/install_command', $installCommand);
        exec($installCommand);

        return compact('adminEmail', 'adminUsername', 'adminPassword');
    }
    private function checkIfAutoInstall()
    {
        $config = $this->storage->read();
        return isset($config->auto_install) && $config->auto_install;
    }

    private function checkIfSymlinkInstall()
    {
        $config = $this->storage->read();
        return isset($config->install_type) && $config->install_type == 'symlinked';
    }


}
