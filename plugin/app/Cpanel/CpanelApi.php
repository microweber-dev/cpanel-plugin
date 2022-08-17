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

}
