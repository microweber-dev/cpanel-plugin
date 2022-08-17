<?php
namespace App\Cpanel;

class CpanelApi
{
    public function getHostingDetailsByDomainName($domainName)
    {
        $details = [];

        $allDomains = $this->getAllDomains();

        if (!empty($allDomains)) {
            foreach ($allDomains as $domain) {
                if ($domain['domain'] == $domainName) {
                    $details = $domain;
                }
            }
        }

        return $details;
    }

    public function getAllDomains()
    {
        $domainRequest = $_SERVER['cpanelApi']->uapi('DomainInfo', 'domains_data', array('format' => 'hash'));
        $domainRequest = $domainRequest['cpanelresult']['result']['data'];
        $domains = array_merge(array($domainRequest['main_domain']), $domainRequest['addon_domains'], $domainRequest['sub_domains']);

        return $domains;
    }

    public function getUsername()
    {
        $username = $_SERVER['cpanelApi']->exec('<cpanel print="$user">');
        return $username['cpanelresult']['data']['result'];
    }

    public function randomPassword($length = 16)
    {
        $alphabet = '!@#abcdef^@%^&*[]-ghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    public function getMysqlRestrictions()
    {
        $data = $this->execUapi( 'Mysql', 'get_restrictions');

        return $data ["result"]['data'];
    }

    public function makeDbPrefix()
    {
        $restriction = $this->getMysqlRestrictions();

        return $restriction['prefix'];
    }

    public function createDatabaseWithUser($dbName, $dbUsername, $dbPassword)
    {
        $createUser = $this->execUapi('Mysql', 'create_user', array('name' => $dbUsername, 'password' => $dbPassword));
        if ($createUser['result']['status'] != 1) {
            return false;
        }

        $createDatabase = $this->execUapi('Mysql', 'create_database', array('name' => $dbName));
        if ($createDatabase['result']['status'] != 1) {
            return false;
        }

        $setPrivileges = $this->execUapi('Mysql', 'set_privileges_on_database', array('user' => $dbUsername, 'database' => $dbName, 'privileges' => 'ALL PRIVILEGES'));
        if ($setPrivileges['result']['status'] != 1) {
            return false;
        }

        return true;
    }

    public function execUapi($module, $function, $args = array())
    {
        $argsString = '';
        foreach ($args as $key => $value) {
            $argsString .= escapeshellarg($key) . '=' . escapeshellarg($value) . ' ';
        }

        $command = "/usr/bin/uapi --output=json $module $function $argsString";

        $json = shell_exec($command);

        return @json_decode($json, true);
    }

}
