<?php

namespace App\Cpanel;

use App\MicroweberVersionsManager;

class InstalledAppsScanner
{
    /**
     * @var CpanelApi
     */
    private $cpanelApi;

    public function __construct()
    {
        $this->cpanelApi = new CpanelApi();
    }

    /**
     * @return array|void
     */
    public function getAllAccounts()
    {
        $allAccounts = array();

        $accounts = $this->cpanelApi->execApi1('listaccts', array('search' => '', 'searchtype' => 'user'));
        if ($accounts and isset($accounts['data']) and isset($accounts['data']['acct'])) {
            foreach ($accounts['data']['acct'] as $account) {
                if (isset($account['user'])) {
                    $user_domains = $this->getAllDomainsByUsername($account['user']);
                }
                if (isset($user_domains) and $user_domains) {
                    $allAccounts = array_merge($allAccounts, $user_domains);

                }
            }
        }
        return $allAccounts;
    }

    /**
     * @param $username
     * @return array|void
     */
    public function getAllDomainsByUsername($username = false)
    {
        $method = false;
        if (isset($this->cpanel) and is_object($this->cpanel) and method_exists($this->cpanel, 'uapi')) {
            $method = 'cpanel';
        } else if (method_exists($this, 'execUapi')) {
            $method = 'execUapi';
        } else if (isset($this->cpanelApi) and is_object($this->cpanelApi) and method_exists($this->cpanelApi, 'execUapi')) {
            $method = 'cpapiexecUapi';
        }

        if (!$method) {
            return;
        }
        $allDomains = array();
        if ($method == 'cpanel') {
            $domaindata = $this->cpanel->uapi('DomainInfo', 'domains_data', array('format' => 'hash'));
        } else if ($method == 'cpapiexecUapi') {
            $domaindata = $this->cpanelApi->execUapi($username, 'DomainInfo', 'domains_data', array('format' => 'hash'));
        } else {
            $domaindata = $this->execUapi($username, 'DomainInfo', 'domains_data', array('format' => 'hash'));
        }
        if ($domaindata) {
            if (isset($domaindata['cpanelresult'])) {
                $domaindata = $domaindata['cpanelresult']['result']['data'];
            } elseif (isset($domaindata['result'])) {
                $domaindata = $domaindata['result']['data'];
            }
            if (isset($domaindata['main_domain'])) {
                $allDomains = array_merge($allDomains, array($domaindata['main_domain']));
            }
            if (isset($domaindata['addon_domains'])) {
                $allDomains = array_merge($allDomains, $domaindata['addon_domains']);
            }
            if (isset($domaindata['sub_domains'])) {
                $allDomains = array_merge($allDomains, $domaindata['sub_domains']);
            }
        }

        return $allDomains;
    }

}
