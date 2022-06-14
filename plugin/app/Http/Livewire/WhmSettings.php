<?php

namespace App\Http\Livewire;

use App\Models\Option;
use Livewire\Component;
use MicroweberPackages\SharedServerScripts\MicroweberSharedPathHelper;

class WhmSettings extends Component
{
    public $supportedTemplates = [];
    public $supportedLanguages = [];
    public $state = [
        'installation_type'=>'default'
    ];

    public function render()
    {
        if (!empty($this->state)) {
            foreach ($this->state as $key=>$value) {
                Option::updateOption($key, $value, 'settings');
            }
        }

        return view('livewire.whm.settings');
    }

    public function mount()
    {
        $sharedPath = new MicroweberSharedPathHelper();
        $sharedPath->setPath(config('whm-cpanel.sharedPaths.app'));

        $this->supportedLanguages = $sharedPath->getSupportedLanguages();
        $this->supportedTemplates = $sharedPath->getSupportedTemplates();

       // mount state
        $this->state = array_merge($this->state, Option::getAll('settings'));
    }
}
