<?php

namespace App\Http\Livewire;

use App\Models\Option;
use Livewire\Component;
use MicroweberPackages\ComposerClient\Client;
use MicroweberPackages\SharedServerScripts\MicroweberWhitelabelSettingsUpdater;

class WhmWhitelabel extends Component
{
    public $state = [];
    public $license = [];
    public $licenseKeyDetails = [
        'register_name' => '',
        'company_name' => '',
        'email' => '',
        'billing_cycle' => '',
        'next_due_date' => '',
        'register_date' => '',
    ];
    public $activeWhitelabel = false;
    public $validationMessageWhitelabelKey = false;
    public $whitelabelLicenseKey = '';
    public $confirmRemoveLicense = false;

    public function render()
    {
        if (!empty($this->state)) {

            foreach ($this->state as $key=>$value) {
                Option::updateOption($key, $value, 'whitelabel');
            }

            $whiteLabelSettings = $this->state;
            $whiteLabelSettings['whmcs_url'] = Option::getOption('whmcs_url', 'settings');

            $whitelabel = new MicroweberWhitelabelSettingsUpdater();
            $whitelabel->setPath(config('whm-cpanel.sharedPaths.app'));
            $whitelabel->apply($whiteLabelSettings);

        }

        return view('livewire.whm.whitelabel');
    }

    public function confirmRemoveLicense()
    {
        $this->confirmRemoveLicense = true;
    }

    public function removeLicense()
    {
        Option::updateOption('license_key', false,'whitelabel_license');
        Option::updateOption('license_key_status', false,'whitelabel_license');
        Option::updateOption('license_key_enabled', false, 'whitelabel_license');

        $this->activeWhitelabel = false;
    }

    public function validateLicense()
    {
        $composerClient = new Client();
        $consumeLicense = $composerClient->consumeLicense($this->whitelabelLicenseKey);
        if ($consumeLicense['valid']) {
            $details = [
                'register_name'=>'None',
                'company_name'=>'None',
                'email'=>'None',
                'product_name'=>'None',
                'register_date'=>'None',
                'next_due_date'=>'None',
                'billing_cycle'=>'None',
            ];
            if (isset(end($consumeLicense['servers'])['details'])) {
                $licenseServerDetails = end($consumeLicense['servers'])['details'];
                $details = [
                    'register_name'=>$licenseServerDetails['registeredname'],
                    'company_name'=>$licenseServerDetails['companyname'],
                    'email'=>$licenseServerDetails['email'],
                    'product_name'=>$licenseServerDetails['productname'],
                    'register_date'=>$licenseServerDetails['regdate'],
                    'next_due_date'=>$licenseServerDetails['nextduedate'],
                    'billing_cycle'=>$licenseServerDetails['billingcycle'],
                ];
            }

            Option::updateOption('license_key', $this->whitelabelLicenseKey, 'whitelabel_license');
            Option::updateOption('license_key_status', 'valid', 'whitelabel_license');
            Option::updateOption('license_key_enabled', true, 'whitelabel_license');
            Option::updateOption('license_key_details', json_encode($details), 'whitelabel_license');

            $this->validationMessageWhitelabelKey = 'License key is valid.';
            $this->activeWhitelabel = true;
        } else {
            Option::updateOption('license_key_enabled', false, 'whitelabel_license');
            $this->validationMessageWhitelabelKey = 'License key is not valid.';
        }

    }

    public function mount()
    {
        // mount state
        $this->state = array_merge($this->state, Option::getAll('whitelabel'));
        $this->license = Option::getAll('whitelabel_license');

        if (isset($this->license['license_key_details'])) {
            $this->licenseKeyDetails = array_merge($this->licenseKeyDetails, json_decode($this->license['license_key_details'], true)) ;
        }

        if (Option::getOption('license_key_status', 'whitelabel_license') == 'valid') {
            $this->activeWhitelabel = true;
        }

    }
}
