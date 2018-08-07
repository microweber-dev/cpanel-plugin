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
       $remove_account = array(
           'blocking' => 1,
           'category' => 'Whostmgr',
           'event'    => 'Accounts::Remove',
           'stage'    => 'pre',
           'hook'     => '/var/cpanel/microweber/mw_hooks.php --remove-account',
           'exectype' => 'script',
       );
       return json_encode(array($add_account, $remove_account));
    }
    
    public function add_account() {
        if(!$this->checkIfAutoInstall()) return;
        $this->input->data->user;
        $this->input->data->args->domain;
        $this->input->data->args->homedir;
    }
    
    public function remove_account() {
        if(!$this->checkIfAutoInstall()) return;
    }
    
    // ----------------------

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
        $createDBCommand = "uapi --user=$user --output=json $module $function $argsString";
    }

    private function install($config) {
        $this->execUapi('Mysql', 'create_database', array('name' => ''));
    }
}
