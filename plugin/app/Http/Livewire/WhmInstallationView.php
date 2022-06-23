<?php

namespace App\Http\Livewire;

use App\Console\Commands\AppInstallationsScan;
use App\Cpanel\CpanelApi;
use App\Models\AppInstallation;
use Livewire\Component;
use MicroweberPackages\SharedServerScripts\MicroweberAppPathHelper;
use MicroweberPackages\SharedServerScripts\MicroweberSharedPathHelper;
use MicroweberPackages\SharedServerScripts\MicroweberInstaller;

class WhmInstallationView extends Component
{
    public $appInstallation;
    public $appSupportedTemplates = [];
    public $appSupportedModules = [];
    public $appSupportedLanguages = [];
    public $appInstallationVersion = [];
    public $appInstallationCreatedAt = [];

    public function render()
    {
        return view('livewire.whm.view_installation');
    }

    public function mount($id)
    {
        $findInstallation = AppInstallation::where('id', $id)->first();
        if ($findInstallation == null) {
            return;
        }

        $appPathHelper = new MicroweberAppPathHelper();
        $appPathHelper->setPath($findInstallation->path);

        $this->appSupportedTemplates = $appPathHelper->getSupportedTemplates();
        $this->appSupportedModules = $appPathHelper->getSupportedModules();
        $this->appSupportedLanguages = $appPathHelper->getSupportedLanguages();
        $this->appInstallationVersion = $appPathHelper->getCurrentVersion();
        $this->appInstallationCreatedAt = $appPathHelper->getCreatedAt();

        $this->appInstallation = $findInstallation;
    }
}
