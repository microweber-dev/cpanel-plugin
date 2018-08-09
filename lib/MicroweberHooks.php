<?php

include_once(__DIR__ . '/MicroweberStorage.php');

class MicroweberHooks
{
    private $input;
    private $storage;

    public function __construct($input) {
        $this->input = $input;
        $this->storage = new MicroweberStorage();
    }

    // Embed hook attribute information.
    public function describe() {
       $add_account = array(
           'category' => 'Whostmgr',
           'event'    => 'Accounts::Create',
           'stage'    => 'post',
           'hook'     => '/var/cpanel/microweber/mw_hooks.php --add-account',
           'exectype' => 'script',
       );
       return json_encode(array($add_account));
    }
    
    public function add_account() {
        if(!$this->checkIfAutoInstall()) return;
        if(!$this->checkIfFeatureEnabled()) return;
        $domain = $this->input->data->args->domain;
        $installPath = $this->input->data->args->homedir;
        $adminEmail = $this->input->data->args->contactemail;
        $adminUsername = $this->input->data->user;
        $adminPassword = $this->input->data->args->password;
        $this->install($domain, $installPath, $adminEmail, $adminUsername, $adminPassword);
    }
    
    // ----------------------

    private function install($domain, $installPath, $adminEmail, $adminUsername, $adminPassword, $dbHost = 'localhost', $dbDriver = 'mysql') {
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

        // Create database
        $this->execUapi('Mysql', 'create_database', array('name' => $dbName));
        $this->execUapi('Mysql', 'create_user', array('name' => $dbUsername, 'password' => $adminPassword));
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
        exec("chmod -R 777 $installPath");

        // Clear cache
        exec("php $installPath/artisan cache:clear");

        // Install Microweber
        $installCommand = "php $installPath/artisan microweber:install $adminEmail $adminUsername $adminPassword $dbHost $dbName $dbUsername $adminPassword $dbDriver -p $dbPrefix -t dream -d 1 -c 1";
        file_put_contents('/tmp/install_command', $installCommand);
        exec($installCommand);

        return compact('adminEmail', 'adminUsername', 'adminPassword');
    }

    // ----------------------

    private function checkIfFeatureEnabled() {
        $user = $this->input->data->user;
        $account = $this->execApi1('accountsummary', compact('user'));
        $account = $account->data->acct[0];
        $pkg = $account->plan;
        $package = $this->execApi1('getpkginfo', compact('pkg'));
        $package = $package->data->pkg;
        $featurelist = $package->FEATURELIST;
        $featurelistData = $this->execApi1('get_featurelist_data', compact('featurelist'));
        $featureHash = $featurelistData->data->features;
        foreach($featureHash as $hash) {
            if($hash->id == 'microweber') {
                return $hash->is_disabled == '0';
            }
        }
        return false;
    }

    private function checkIfAutoInstall() {
        $config = $this->storage->read();
        return isset($config->auto_install) && $config->auto_install;
    }

    private function checkIfSymlinkInstall() {
        $config = $this->storage->read();
        return isset($config->install_type) && $config->install_type == 'symlinked';
    }

    private function execUapi($module, $function, $args = array()) {
        $user = $this->input->data->user;
        $argsString = '';
        foreach($args as $key=>$value) {
            $argsString = urlencode($key) . '=' . urlencode($value);
        }
        $command = "uapi --user=$user --output=json $module $function $argsString";
        $json = shell_exec($command);
        return json_decode($json);
    }

    private function execApi1($function, $args) {
        $argsString = '';
        foreach($args as $key=>$value) {
            $argsString = urlencode($key) . '=' . urlencode($value);
        }
        $command = "whmapi1 --output=json $function $argsString";
        $json = shell_exec($command);
        return json_decode($json);
    }
}
