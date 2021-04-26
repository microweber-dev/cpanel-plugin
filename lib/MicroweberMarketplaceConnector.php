<?php

class MicroweberMarketplaceConnector
{
    /**
     * Package manager urls
     *
     * @var array
     */
    public $package_urls = [
        'https://packages.microweberapi.com/packages.json',
        'https://private-packages.microweberapi.com/packages.json'
    ];
    public $package_urls_orig = [
        'https://packages.microweberapi.com/packages.json',
        'https://private-packages.microweberapi.com/packages.json'
    ];
    /**
     * Set WHMCS Url
     * @var bool
     */
    public $whmcs_url = false;

    public function set_whmcs_url($url)
    {
        if (!empty($url)) {
            $this->whmcs_url = $url;
            $this->update_package_urls();
        }
    }

    public function update_package_urls()
    {

        $whmcsUrl = $this->whmcs_url . '/index.php?m=microweber_addon&function=get_package_manager_urls';
        $whmcsPackageUrls = $this->_get_content_from_url($whmcsUrl);
        $whmcsPackageUrls = json_decode($whmcsPackageUrls, TRUE);
        $urls_with_link = [];

        if (is_array($whmcsPackageUrls) && !empty($whmcsPackageUrls)) {
            foreach ($whmcsPackageUrls as $whmcsPackageUrl) {
                if (filter_var($whmcsPackageUrl, FILTER_VALIDATE_URL) !== FALSE) {
                    $urls_with_link[] = $whmcsPackageUrl;

                }
            }


        }

        if ($urls_with_link) {
            $this->set_package_urls($whmcsPackageUrls);
        }

    }

    public function add_package_urls($urls)
    {
           if (is_array($urls) && !empty($urls)) {
            foreach ($urls as $url) {
                $this->add_package_url($url);
            }
        }
    }

    public function set_package_urls($urls)
    {
        if (is_array($urls) && !empty($urls)) {
            $this->package_urls = [];
            foreach ($urls as $url) {
                $this->add_package_url($url);
            }
        }
    }

    public function add_package_url($url)
    {

        $url = trim($url);
        $url = str_replace(',', false, $url);
        $url = rtrim($url, "/") . '/';

        if (!stristr($url, 'packages.json')) {
            $url = ($url . "/") . 'packages.json';
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            return;
        }

        if (empty($url)) {
            return;
        }

        $this->package_urls[] = $url;
    }


    /**
     * Get package urls
     * @return string[]
     */
    public function get_packages_urls()
    {
        return $this->package_urls;
    }

    /**
     * Get available packages
     *
     * @return array[]|unknown
     */
    public function get_packages()
    {
        $allowed_package_types = array(
            'microweber-template',
            'microweber-module'
        );
        $return = array();
        $packages = array();
        $packages_by_type = array();


        if ($this->package_urls) {
            foreach ($this->package_urls as $url) {
                $package_manager_resp = $this->_get_content_from_url($url);
                $package_manager_resp = @json_decode($package_manager_resp, true);
                if ($package_manager_resp and isset($package_manager_resp['packages']) and is_array($package_manager_resp['packages'])) {
                    $packages = array_merge($packages, $package_manager_resp['packages']);
                }
            }
        }
        if ($packages) {
            foreach ($packages as $pk => $package) {
                $version_type = false;
                $package_item = $package;
                $last_item = array_pop($package_item);
                if (isset($last_item['type'])) {
                    $version_type = $last_item['type'];
                    $package['latest_version'] = $last_item;
                }
                if ($version_type and in_array($version_type, $allowed_package_types)) {
                    $package_is_allowed = true;
                    $return[$pk] = $package;
                    if (!isset($packages_by_type[$version_type])) {
                        $packages_by_type[$version_type] = array();
                    }
                    $packages_by_type[$version_type][$pk] = $package;
                }
            }
        }

        return $packages_by_type;
    }

    /**
     * Get available templates
     *
     * @return boolean[]|unknown[]
     */
    public function get_templates()
    {
        $templates = $this->get_packages();

        $return = array();
        if ($templates and isset($templates["microweber-template"])) {
            foreach ($templates["microweber-template"] as $pk => $template) {
                $package_item = $template;
                $package_item_version = $package_item;
                $package_item_version = array_reverse($package_item_version);
                $last_item = false;
                foreach ($package_item_version as $package_item_version_key => $package_item_version_data) {
                    if (!$last_item and $package_item_version_data and isset($package_item_version_data['version']) and $package_item_version_data['version'] != 'dev-master' and is_numeric($package_item_version_data['version'])) {
                        $last_item2 = $package_item_version_data;
                        $last_item = $last_item2;
                    }
                }

                if ($last_item) {
                    $template['latest_version'] = $last_item;
                    $screenshot = '';
                    $readme = '';
                    if (isset($template['latest_version']) and isset($template['latest_version']['extra']) and isset($template['latest_version']['extra']['_meta']) and isset($template['latest_version']['extra']['_meta']['screenshot'])) {
                        $screenshot = $template['latest_version']['extra']['_meta']['screenshot'];
                    }
                    if (isset($template['latest_version']) and isset($template['latest_version']['extra']) and isset($template['latest_version']['extra']['_meta']) and isset($template['latest_version']['extra']['_meta']['readme'])) {
                        $readme = $template['latest_version']['extra']['_meta']['readme'];
                    }
                    $return[$pk] = $template;
                }
            }
        }

        return $return;
    }

    public function get_templates_download_urls()
    {
        $download_urls = [];

        $templates = $this->get_templates();
        if (is_array($templates) && !empty($templates)) {
            foreach ($templates as $template) {
                if (isset($template['latest_version'])) {

                    $download_urls[] = [
                        'name' => $template['latest_version']['name'],
                        'target_dir' => $template['latest_version']['target-dir'],
                        'download_url' => $template['latest_version']['dist']['url']
                    ];

                }
            }
        }

        return $download_urls;
    }

    /**
     * Get content from url
     * @param unknown $url
     * @return unknown
     */
    private function _get_content_from_url($url)
    {
        if (in_array('curl', get_loaded_extensions())) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return data inplace of echoing on screen
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // Skip SSL Verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        } else {
            return @file_get_contents($url);
        }
    }
}
