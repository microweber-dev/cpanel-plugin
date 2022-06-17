<?php

namespace App\Cpanel;

use App\MicroweberVersionsManager;
use MicroweberPackages\SharedServerScripts\MicroweberInstallationsRecursiveScanner;

class InstalledAppsScanner
{
    private $cpanelApi;

    public function __construct()
    {
        $this->cpanelApi = new CpanelApi();
    }

    public function scanAllAccounts()
    {
        $return = array();

        $accounts = $this->cpanelApi->execApi1('listaccts', array('search' => '', 'searchtype' => 'user'));
        if ($accounts and isset($accounts['data']) and isset($accounts['data']['acct'])) {
            foreach ($accounts['data']['acct'] as $account) {
                if (isset($account['user'])) {
                    $user_domains = $this->scanByUsername($account['user']);
                }
                if (isset($user_domains) and $user_domains) {
                    $return = array_merge($return, $user_domains);

                }
            }
        }
        return $return;
    }

    public function scanByUsername($username = false)
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

        $installations = array();
        foreach ($allDomains as $domain) {

           $recursiveScan = new MicroweberInstallationsRecursiveScanner();
           $recursiveScan->setPath($domain['documentroot']);

           foreach ($recursiveScan->scan() as $installation) {

                $domain['path'] = $installation['path'];
                $domain['created_at'] = $installation['created_at'];
                $domain['version'] = $installation['version'];

                if ($installation['is_symlink']) {
                    $domain['is_symlink'] = 1;
                    $domain['symlink_target'] = true;
                } else {
                    $domain['is_symlink'] = 0;
                    $domain['symlink_target'] = false;
                }

                $installations[] = $domain;
            }
        }

        return $installations;
    }
}
