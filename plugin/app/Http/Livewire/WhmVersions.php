<?php

namespace App\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\SharedServerScripts\MicroweberDownloader;
use MicroweberPackages\SharedServerScripts\MicroweberAppPathHelper;

class WhmVersions extends Component
{
    public $currentVersionOfApp = 0;
    public $latestVersionOfApp = 0;
    public $latestDownloadDateOfApp = 0;

    public $supportedTemplates = [];
    public $supportedLanguages = [];

    public function checkForUpdate()
    {
        $sharedAppPath = config('whm-cpanel.sharedPaths.app');
        if(!is_dir(dirname($sharedAppPath))) {
            mkdir(dirname($sharedAppPath));
        }

        $downloader = new MicroweberDownloader();
        $status = $downloader->download(config('whm-cpanel.sharedPaths.app'));

    }

    public function render()
    {
        $sharedPath = new MicroweberAppPathHelper();
        $sharedPath->setPath(config('whm-cpanel.sharedPaths.app'));

        $this->supportedLanguages = $sharedPath->getSupportedLanguages();
        $this->supportedTemplates = $sharedPath->getSupportedTemplates();
        $this->latestVersionOfApp = $sharedPath->getCurrentVersion();
        $this->currentVersionOfApp = $sharedPath->getCurrentVersion();
        $this->latestDownloadDateOfApp = $sharedPath->getCreatedAt();

        return view('livewire.whm.versions');
    }
}
