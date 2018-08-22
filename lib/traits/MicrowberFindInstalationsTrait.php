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

        $return = array();
        foreach ($allDomains as $key => $domain) {
            $mainDir = $domain['documentroot'];
            $find_version = new MicroweberVersionsManager($mainDir);
            $config_file = $mainDir . "/config/microweber.php";
            $config = file_exists($config_file);
            $is_symlink = $find_version->isSymlinked();
            $symlink_target = false;
            if ($is_symlink) {
                $symlink_target = readlink($mainDir . "/src");
                $symlink_target = dirname($symlink_target);
            }
            if (!$config) {
                continue;
            }


            //  echo $stat['ctime'];


            $version = $find_version->getCurrentVersion();
            if ($version) {
                $filectime = filectime($config_file);
                $format = "Y-m-d H:i:s";
                $domain['created_at'] = date($format, $filectime);
                $domain['version'] = $version;
                if ($is_symlink) {
                    $domain['is_symlink'] = 1;
                    $domain['symlink_target'] = $symlink_target;
                } else {
                    $domain['is_symlink'] = 0;
                    $domain['symlink_target'] = false;

                }
                $return[$key] = $domain;
            }
        }
        return $return;
    }
}