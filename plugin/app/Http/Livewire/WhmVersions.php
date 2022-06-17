<?php

namespace App\Http\Livewire;

use App\Models\Option;
use Livewire\Component;
use MicroweberPackages\ComposerClient\Client;
use MicroweberPackages\SharedServerScripts\MicroweberDownloader;
use MicroweberPackages\SharedServerScripts\MicroweberSharedPathHelper;
use MicroweberPackages\SharedServerScripts\MicroweberTemplatesDownloader;

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

        $composerClient = new Client();
        if (Option::getOption('license_key_status', 'whitelabel_license') == 'valid') {
            $composerClient->addLicense([
                'local_key' => Option::getOption('license_key', 'whitelabel_license')
            ]);
        }

        $downloader = new MicroweberDownloader();
        $downloader->setComposerClient($composerClient);
        $status = $downloader->download(config('whm-cpanel.sharedPaths.app'));

        $downloader = new MicroweberTemplatesDownloader();
        $downloader->setComposerClient($composerClient);
        $status = $downloader->download(config('whm-cpanel.sharedPaths.templates'));

    }

    public function render()
    {
        $sharedPath = new MicroweberSharedPathHelper();
        $sharedPath->setPath(config('whm-cpanel.sharedPaths.app'));

        $this->supportedLanguages = $sharedPath->getSupportedLanguages();
        $this->supportedTemplates = $sharedPath->getSupportedTemplates();
        $this->latestVersionOfApp = $sharedPath->getCurrentVersion();
        $this->currentVersionOfApp = $sharedPath->getCurrentVersion();
        $this->latestDownloadDateOfApp = $sharedPath->getLastDownloadDate();

        return view('livewire.whm.versions');
    }
}
