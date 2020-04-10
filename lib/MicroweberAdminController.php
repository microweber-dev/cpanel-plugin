<?php
include_once(__DIR__ . '/MicroweberHooks.php');
include_once(__DIR__ . '/MicroweberCpanelApi.php');
include_once(__DIR__ . '/MicroweberLogger.php');
require_once(__DIR__ . '/traits/MicrowberFindInstalationsTrait.php');
require_once(__DIR__ . '/traits/MicrowberLicenseDataTrait.php');


class MicroweberAdminController
{
    use MicrowberFindInstalationsTrait;
    use MicrowberLicenseDataTrait;

    public $logger = null;
    public $cpapi = null;

    public function __construct()
    {
        $this->cpapi = new MicroweberCpanelApi();;
        $this->logger = new MicroweberLogger();
    }

    public function get_installations_across_server()
    {
        $return = array();
        // whmapi1 listaccts search=username searchtype=user
        $accounts = $this->cpapi->execApi1('listaccts', array('search' => '', 'searchtype' => 'user'));
        if ($accounts and isset($accounts['data']) and isset($accounts['data']['acct'])) {
            foreach ($accounts['data']['acct'] as $account) {
                if (isset($account['user'])) {
                    $user_domains = $this->findInstalations($account['user']);
                }
                if (isset($user_domains) and $user_domains) {
                    $return = array_merge($return, $user_domains);

                }
            }
        }
        return $return;
    }

}