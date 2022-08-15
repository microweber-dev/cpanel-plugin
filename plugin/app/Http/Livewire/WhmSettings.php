<?php

namespace App\Http\Livewire;

use App\Models\Option;
use Livewire\Component;
use MicroweberPackages\SharedServerScripts\MicroweberAppPathHelper;
use MicroweberPackages\SharedServerScripts\MicroweberWhitelabelSettingsUpdater;
use MicroweberPackages\SharedServerScripts\MicroweberWhmcsConnector;

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
                Option::updateOption($key, trim($value), 'settings');
            }

            $whmcsUrl = Option::getOption('whmcs_url', 'settings');
            if (isset($this->state['whmcs_url'])) {
                if ($this->state['whmcs_url'] != $whmcsUrl) {

                    // Update Whmcs Connector
                    $whmcs = new MicroweberWhmcsConnector();
                    $whmcs->setPath(config('whm-cpanel.sharedPaths.app'));
                    $whmcs->setUrl($whmcsUrl);
                    $whmcs->applySettingsToPath();

                    // Update Whitelabel
                    $whiteLabelSettings = Option::getAll('whitelabel');
                    $whiteLabelSettings['whmcs_url'] = Option::getOption('whmcs_url', 'settings');

                    $whitelabel = new MicroweberWhitelabelSettingsUpdater();
                    $whitelabel->setPath(config('whm-cpanel.sharedPaths.app'));
                    $whitelabel->apply($whiteLabelSettings);

                }
            }
        }

        return view('livewire.whm.settings');
    }

    public function mount()
    {
        $sharedPath = new MicroweberAppPathHelper();
        $sharedPath->setPath(config('whm-cpanel.sharedPaths.app'));

        $this->supportedLanguages = $sharedPath->getSupportedLanguages();
        $this->supportedTemplates = $sharedPath->getSupportedTemplates();

       // mount state
        $this->state = array_merge($this->state, Option::getAll('settings'));
    }
}
