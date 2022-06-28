<?php

namespace App\Http\Livewire;

use App\Models\Option;
use Livewire\Component;
use MicroweberPackages\ComposerClient\Client;
use MicroweberPackages\SharedServerScripts\MicroweberWhitelabelUpdater;

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
                Option::updateOption($key, $value, 'whitelabel');
            }

            $whitelabel = new MicroweberWhitelabelUpdater();
            $whitelabel->setPath(config('whm-cpanel.sharedPaths.app'));
            $whitelabel->apply($this->state);

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

        $this->activeWhitelabel = false;
    }

    public function validateLicense()
    {
        $composerClient = new Client();
        $consumeLicense = $composerClient->consumeLicense($this->whitelabelLicenseKey);
        if ($consumeLicense['valid']) {

            Option::updateOption('license_key', $this->whitelabelLicenseKey, 'whitelabel_license');
            Option::updateOption('license_key_status', 'valid', 'whitelabel_license');

            $this->validationMessageWhitelabelKey = 'License key is valid.';
            $this->activeWhitelabel = true;
        } else {
            $this->validationMessageWhitelabelKey = 'License key is not valid.';
        }

    }

    public function mount()
    {
        // mount state
        $this->state = array_merge($this->state, Option::getAll('whitelabel'));

        if (Option::getOption('license_key_status', 'whitelabel_license') == 'valid') {
            $this->activeWhitelabel = true;
        }

    }
}
