<?php

namespace MicroweberPackages\ComposerClient;

use MicroweberPackages\App\Models\SystemLicenses;
use MicroweberPackages\ComposerClient\Traits\FileDownloader;

class Client
{
    use FileDownloader;

    public $licenses = [];
    public $packageServers = [
        'https://packages.microweberapi.com/packages.json',
    ];

    public function __construct()
    {
        //
    }

    public function setLicenses(array $licenses)
    {
        $this->licenses = $licenses;
    }

    public function addLicense($license)
    {
        $this->licenses[] = $license;
    }

    public function consumeLicense($license)
    {
        $status = 'invalid';
        $servers = [];
        $valid = false;

        foreach ($this->packageServers as $package) {

            $singlePackageParseUrl = parse_url($package);
            $licenseCheckPackageUrl = $singlePackageParseUrl['scheme'] . '://' . $singlePackageParseUrl['host'] . '/licenses/check?key=' . $license;

            $curl = curl_init();

            $headers = $this->prepareHeaders();

            $opts = [
                CURLOPT_URL => $licenseCheckPackageUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "",
            ];
            if (!empty($headers)) {
                $opts[CURLOPT_HTTPHEADER] = $headers;
            }

            curl_setopt_array($curl, $opts);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                $servers[$singlePackageParseUrl['host']] = ["error" => "cURL Error #:" . $err];
            } else {
                $jsonResponse = @json_decode($response, true);
                if (!empty($jsonResponse)) {
                    $servers[$singlePackageParseUrl['host']] = $jsonResponse;

                    $status = $jsonResponse['details']['status'];
                    if ($status == 'Active') {
                        $valid = true;
                    }
                }
            }
        }

        return ['valid'=>$valid, 'status'=>$status, 'servers'=>$servers];
    }

    public function getPackageByName($packageName, $packageVersion = false) {

        $foundedPackage = [];
        foreach ($this->packageServers as $package) {

            $singlePackageParseUrl = parse_url($package);
            $singlePackageUrl = $singlePackageParseUrl['scheme'] .'://'. $singlePackageParseUrl['host']. '/packages/'.$packageName.'.json';

            $packageFile = $this->getPackageFile($singlePackageUrl);

            if (!empty($packageFile)) {
                foreach ($packageFile as $name => $versions) {
                    if (!is_array($versions)) {
                        continue;
                    }
                    if ($packageName == $name) {

                        $versions['latest'] = end($versions);

                        if ($packageVersion) {
                            foreach ($versions as $version => $versionData) {
                                if ($packageVersion == $version) {
                                    $foundedPackage = $versionData;
                                    break;
                                }
                            }
                        } else {
                            $foundedPackage = end($versions);
                        }
                    }

                }
            }
        }

        return $foundedPackage;
    }

    public function search($filter = array())
    {
        if (!empty($filter) && isset($filter['require_name'])) {

            $packageName = $filter['require_name'];

            $packageVersion = false;
            if (isset($filter['require_version'])) {
                $packageVersion = $filter['require_version'];
            }

            return $this->getPackageByName($packageName, $packageVersion);
        }

        foreach ($this->packageServers as $package) {
            $packageFile = $this->getPackageFile($package);
            if (!empty($packageFile)) {
                return $packageFile;
            }
        }

        return [];
    }

    public function prepareHeaders()
    {
        $headers = [];

        if (defined('MW_VERSION')) {
            $headers[] = "x-mw-version: " . MW_VERSION;
        }

        if (function_exists('site_url')) {
            $headers[] = "x-mw-site-url: " . base64_encode(site_url());
        }

        if (!empty($this->licenses)) {
            $headers[] = "Authorization: Basic " . base64_encode('license:' . base64_encode(json_encode($this->licenses)));
        }

        if (function_exists('mw_root_path')) {
            $headers[] = "x-mw-root-path: " . base64_encode(mw_root_path());
        }

        return $headers;
    }

    public function getPackageFile($packageUrl)
    {
        $curl = curl_init();

        $headers = $this->prepareHeaders();

        $opts = [
            CURLOPT_URL => $packageUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
        ];
        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = $headers;
        }

        curl_setopt_array($curl, $opts);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ["error" => "cURL Error #:" . $err];
        } else {
            $getPackages = json_decode($response, true);

            if (isset($getPackages['packages']) && is_array($getPackages['packages'])) {
                return $getPackages['packages'];
            }
            return [];
        }
    }

    public function notifyPackageInstall($package)
    {
        $packageUrl = false;
        if (isset($package['notification-url'])) {
            $packageUrl = $package['notification-url'];
        }

        if(!$packageUrl){
            return;
        }

        $curl = curl_init();

        $headers = $this->prepareHeaders();

        $opts = [
            CURLOPT_URL => $packageUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
        ];
        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = $headers;
        }

        curl_setopt_array($curl, $opts);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ["error" => "cURL Error #:" . $err];
        } else {
            return @json_decode($response, true);

        }

    }


}
