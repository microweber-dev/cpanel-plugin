<?php

trait MicrowberFindInstalationsTrait
{
    public function findInstalations($username = false)
    {

        $method = false;
        if (isset($this->cpanel) and is_object($this->cpanel) and method_exists($this->cpanel, 'uapi')) {
            $method = 'cpanel';
        } else if (method_exists($this, 'execUapi')) {
            $method = 'execUapi';
        } else if (isset($this->cpapi) and is_object($this->cpapi) and method_exists($this->cpapi, 'execUapi')) {
            $method = 'cpapiexecUapi';
        }

        if (!$method) {
            return;
        }
        $allDomains = array();
        if ($method == 'cpanel') {
            $domaindata = $this->cpanel->uapi('DomainInfo', 'domains_data', array('format' => 'hash'));
        } else if ($method == 'cpapiexecUapi') {
            $domaindata = $this->cpapi->execUapi($username, 'DomainInfo', 'domains_data', array('format' => 'hash'));

        } else {
            $domaindata = $this->execUapi($username, 'DomainInfo', 'domains_data', array('format' => 'hash'));
        }
        if ($domaindata) {
            if(isset( $domaindata['cpanelresult'])){
                $domaindata = $domaindata['cpanelresult']['result']['data'];
            }  elseif(isset( $domaindata['result'])){
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

        $return = array();
        foreach ($allDomains as $key => $domain) {

            $mainDir = $domain['documentroot'];
            $config = file_exists("$mainDir/config/microweber.php");
            $version_file = file_exists("$mainDir/version.txt");
            if (!$config) {
                continue;
            }
            if (!$version_file) {
                $version = 'unknown';
            } else {
                $version = file_get_contents("$mainDir/version.txt");

            }
            $domain['version'] = strip_tags($version);
            $return[$key] = $domain;
        }
        return $return;
    }
}