<?php
include_once(__DIR__ . '/MicroweberHooks.php');
include_once(__DIR__ . '/MicroweberCpanelApi.php');
include_once(__DIR__ . '/MicroweberLogger.php');
require_once(__DIR__ . '/traits/MicrowberFindInstalationsTrait.php');


class MicroweberPluginController
{
    use MicrowberFindInstalationsTrait;

    public $logger = null;

    public function __construct($cpanel)
    {
        $this->cpanel = $cpanel;
        $this->logger = new MicroweberLogger();

    }

    public function install()
    {


        $settings_from_admin = new MicroweberStorage();
        $settings_from_admin = $settings_from_admin->read();


        $is_symlinked = false;
        if (isset($settings_from_admin['install_type']) and $settings_from_admin['install_type'] == 'symlinked') {
            $is_symlinked = true;

        }


        $adminEmail = $_POST['admin_email'];
        $adminUsername = $_POST['admin_username'];
        $adminPassword = $_POST['admin_password'];
        $dbDriver = $_POST['db_driver'];
        //$dbDriver = 'mysql';
        $dbHost = 'localhost';


        // Prepare data
        $domainData = htmlspecialchars_decode($_POST["domain"]);
        $domainData = @json_decode($domainData);


        // $domainData = json_decode($_POST['domain']);
        $installPath = $domainData->documentroot;
        // $domainData = json_decode($_POST['domain']);


        $user = $this->getUsername();
        $dbPrefix = $this->makeDBPrefix();


        $dbNameLength = 16 - strlen($dbPrefix);
        $dbName = str_replace('.', '_', $domainData->domain);
        $dbName = $dbPrefix . substr($dbName, 0, $dbNameLength);
        $dbUsername = $dbName;

        $dbHost = $this->cpanel->uapi('Mysql', 'locate_server');
        $dbHost = $dbHost['cpanelresult']['result']['data']['remote_host'];
        if ($_POST['express'] == '0') {
            $dbDriver = $_POST['db_driver'];
            $dbHost = $_POST['db_host'];
            $dbName = $_POST['db_name'];
            $dbUsername = $_POST['db_username'];
            $dbPassword = $_POST['db_password'];
        }

        $domain = $domainData->domain;

        //@todo fix $sourcepath to be from /usr/share
        $sourcepath = $domainData->homedir;
        $sourcepath = $domainData->homedir;
        $sourcepath = '/usr/share/microweber/latest';

        $installPath = $domainData->documentroot;


        $version_manager = new MicroweberVersionsManager($sourcepath);
        if (!$version_manager->hasDownloaded()) {
            $version_manager->download();
        }
        if (!$version_manager->hasDownloaded()) {
            return;
        }


        $cpapi = new MicroweberCpanelApi();
        // $dbPassword = $dbPass = $cpapi->randomPassword(12);


        if ($dbDriver == 'sqlite') {
            $this->log('Using sqlite for ' . $dbUsername);
            $dbHost = 'localhost';
          //  $dbName = 'storage/database.sqlite';
            $dbName = 'storage/database_'. str_replace('.', '_', $domain).'_'.uniqid().'.sqlite';

        } else {


            $dbNameLength = 15;
            $dbPrefix = $cpapi->makeDbPrefixFromUsername(false);

            $dbName = $dbPrefix . str_replace('.', '', $domain);
            $dbName = substr($dbName, 0, $dbNameLength);

            $dbUsername = $dbName;
            $dbPassword = $dbPass = $cpapi->randomPassword(12);

            $this->log('Creating database user ' . $dbUsername);
            $cpapi->execUapi(false, 'Mysql', 'create_user', array('name' => $dbUsername, 'password' => $dbPass));


            $this->log('Creating database ' . $dbName);
            $cpapi->execUapi(false, 'Mysql', 'create_database', array('name' => $dbName));

            $this->log('Setting privileges ' . $dbUsername);
            $cpapi->execUapi(false, 'Mysql', 'set_privileges_on_database', array('user' => $dbUsername, 'database' => $dbName, 'privileges' => 'ALL PRIVILEGES'));
        }


        //php artisan microweber:install admin@site.com admin password storage/database1.sqlite microweber microweber nopass sqlite -p site_ -t liteness -d 1


        $opts = array();
        $opts['source_folder'] = $sourcepath;
        $opts['public_html_folder'] = $installPath;
        $opts['chown_user'] = $user;
        $opts['user'] = $adminUsername;
        $opts['pass'] = $adminPassword;
        $opts['email'] = $adminEmail;
        $opts['database_driver'] = $dbDriver;
        $opts['database_user'] = $dbUsername;
        $opts['database_password'] = $dbPassword;
        $opts['database_table_prefix'] = $dbPrefix;
        $opts['database_name'] = $dbName;
        $opts['database_host'] = $dbHost;
        // $opts['debug_email'] = 'peter@microweber.com';

        $opts['default_template'] = 'dream'; //@todo get from settings
        $opts['config_only'] = 1; //@todo get from settings

        $opts['is_symlink'] = $is_symlinked;

        $opts['extra_config'] = $settings_from_admin;

//        $install_opts = array();
//        $opts['options'] = $install_opts;
        $do_install = new MicroweberInstallCommand();
        $do_install = $do_install->install($opts);
        return $do_install;


    }

    public function log($msg)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($msg);
        }
    }


    public function uninstall()
    {
        // Prepare data
        $domainData = json_decode($_POST['domain']);
        $installPath = $domainData->documentroot;
        $dbUsername = $this->getUsername();
        $dbPrefix = $this->makeDBPrefix();
        $dbNameLength = 16 - strlen($dbPrefix);
        $dbName = str_replace('.', '_', $domainData->domain);
        $dbName = $dbPrefix . substr($dbName, 0, $dbNameLength);
        $dbUsername = $dbName;

        // Create empty install directory
        exec("rm -rf $installPath");
        mkdir($installPath);

        // Delete database
        $this->cpanel->uapi('Mysql', 'delete_database', array('name' => $dbName));
        $this->cpanel->uapi('Mysql', 'delete_user', array('name' => $dbUsername));
    }

    public function getUsername()
    {
        $username = $this->cpanel->exec('<cpanel print="$user">');
        return $username['cpanelresult']['data']['result'];
    }

    public function makeDBPrefix()
    {
        return $this->cpanel->makeDbPrefixFromUsername(false);

        //return substr($this->getUsername(), 0, 8) . '_';
    }


}