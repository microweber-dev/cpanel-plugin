<?php

include_once(__DIR__ . '/MicroweberStorage.php');

class MicroweberHooks
{
    private $storage;

    public function __construct() {
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
       $add_domain = array(
           'category' => 'Cpanel',
           'event'    => 'Api2::AddonDomain::addaddondomain',
           'stage'    => 'post',
           'hook'     => '/var/cpanel/microweber/mw_hooks.php --add-domain',
           'exectype' => 'script',
       );
       $remove_domain = array(
           'blocking' => 1,
           'category' => 'Cpanel',
           'event'    => 'Api2::AddonDomain::deladdondomain',
           'stage'    => 'pre',
           'hook'     => '/var/cpanel/microweber/mw_hooks.php --remove-domain',
           'exectype' => 'script',
       );
       $add_subdomain = array(
           'category' => 'Cpanel',
           'event'    => 'Api2::SubDomain::addsubdomain',
           'stage'    => 'post',
           'hook'     => '/var/cpanel/microweber/mw_hooks.php --add-subdomain',
           'exectype' => 'script',
       );
       $remove_subdomain = array(
           'blocking' => 1,
           'category' => 'Cpanel',
           'event'    => 'Api2::AddonDomain::deladdondomain',
           'stage'    => 'pre',
           'hook'     => '/var/cpanel/microweber/mw_hooks.php --remove-subdomain',
           'exectype' => 'script',
       );
       $move_subdomain = array(
           'category' => 'Cpanel',
           'event'    => 'Api2::AddonDomain::changedocroot',
           'stage'    => 'post',
           'hook'     => '/var/cpanel/microweber/mw_hooks.php --move-subdomain',
           'exectype' => 'script',
       );
       return json_encode(array($add_account, $remove_account, $add_domain, $remove_domain, $add_subdomain, $remove_subdomain, $move_subdomain));
    }
    
    public function add_account() {
    }
    
    public function remove_account() {
    }

    public function add_domain() {
    }

    public function remove_domain() {
    }

    public function add_subdomain() {
    }

    public function remove_subdomain() {
    }

    public function move_subdomain() {
    }
}
