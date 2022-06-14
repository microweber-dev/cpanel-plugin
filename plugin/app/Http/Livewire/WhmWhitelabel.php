<?php

namespace App\Http\Livewire;

use App\Models\Option;
use Livewire\Component;
use MicroweberPackages\ComposerClient\Client;

class WhmWhitelabel extends Component
{
    public $state = [];
    public $validWhitelabel = false;
    public $whitelabelLicenseKey = '';

    public function render()
    {
        if (!empty($this->state)) {
            foreach ($this->state as $key=>$value) {
                Option::updateOption($key, $value);
            }
        }

        return view('livewire.whm.whitelabel');
    }

    public function validateLicense()
    {
        $composerClient = new Client();
        $consumeLicense = $composerClient->consumeLicense($this->whitelabelLicenseKey);

        dd($consumeLicense);
    }

    public function mount()
    {
        // mount state
        $this->state = array_merge($this->state, Option::getAll());
    }
}
