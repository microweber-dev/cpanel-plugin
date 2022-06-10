<?php

namespace App;

class MicroweberWhmcsConnector
{
    public $installer;

    public function __construct($installer = false)
    {

        if (!$installer) {
            $installer = new MicroweberInstallCommand();
        }
        $this->installer = $installer;

    }


    public function getSettings()
    {
        $extras_folder = $this->installer->getExtrasDir();
        $storage_whmcs_connector_module_settings_json_file = $extras_folder . 'userfiles/modules/whmcs_connector/settings.json';
        $storage_whmcs_connector_settings_storage = new MicroweberStorage($storage_whmcs_connector_module_settings_json_file);
        $storage_whmcs_connector_settings = $storage_whmcs_connector_settings_storage->read();
        return $storage_whmcs_connector_settings;
    }

    public function saveSettings($data_to_save)
    {
        $extras_folder = $this->installer->getExtrasDir();
        $storage_whmcs_connector_module_settings_json_file = $extras_folder . 'userfiles/modules/whmcs_connector/settings.json';
        $storage_whmcs_connector_settings_storage = new MicroweberStorage($storage_whmcs_connector_module_settings_json_file);
        $storage_whmcs_connector_settings_storage->save($data_to_save);
    }


    public function getUrl()
    {
        $settings = $this->getSettings();


        if ($settings and isset($settings['whmcs_url'])) {
            return rtrim($settings['whmcs_url'], "/") . '/';
        }

        if ($settings and isset($settings['url'])) {
            return rtrim($settings['url'], "/") . '/';
        }
    }


    public function getDomainConfig($domain)
    {
        $url_base = $this->getUrl();

        if ($url_base) {
            $url = $url_base . "index.php?m=microweber_addon&function=get_domain_template_config&domain=" . $domain;
            $config = $this->_exec_request($url);
            if ($config) {
                return $config;
            }
        }
    }


    private function _exec_request($url)
    {


        $postfields = array();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        if ($postfields) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $data = curl_exec($ch);

        curl_close($ch);
        // $data = file_get_contents($url);
        $data = @json_decode($data, true);

        return $data;


    }


}
