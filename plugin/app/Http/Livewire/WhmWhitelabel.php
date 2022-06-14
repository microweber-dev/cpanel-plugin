<?php

namespace App\Http\Livewire;

use App\Models\Option;
use Livewire\Component;
use MicroweberPackages\ComposerClient\Client;

class WhmWhitelabel extends Component
{
    public $state = [];
    public $activeWhitelabel = false;
    public $validationMessageWhitelabelKey = false;
    public $whitelabelLicenseKey = '';
    public $confirmRemoveLicense = false;

    public function render()
    {
        if (!empty($this->state)) {
            foreach ($this->state as $key=>$value) {
                Option::updateOption($key, $value);
            }
        }

        return view('livewire.whm.whitelabel');
    }

    public function confirmRemoveLicense()
    {
        $this->confirmRemoveLicense = true;
    }

    public function removeLicense()
    {
        $this->activeWhitelabel = true;
        Option::updateOption('wl_license_key', '');
        Option::updateOption('wl_license_key_status', '');
    }

    public function validateLicense()
    {
        $composerClient = new Client();
        $consumeLicense = $composerClient->consumeLicense($this->whitelabelLicenseKey);
        if ($consumeLicense['valid']) {

            Option::updateOption('wl_license_key', $this->whitelabelLicenseKey);
            Option::updateOption('wl_license_key_status', 'valid');

            $this->validationMessageWhitelabelKey = 'License key is valid.';
            $this->activeWhitelabel = true;
        } else {
            $this->validationMessageWhitelabelKey = 'License key is not valid.';
        }

    }

    public function mount()
    {
        // mount state
        $this->state = array_merge($this->state, Option::getAll());

        $licenseKeyStatus = Option::getOption('wl_license_key_status');
        if ($licenseKeyStatus == 'valid') {
            $this->activeWhitelabel = true;
        }

    }
}
