<?php

namespace App\Http\Livewire;

use App\Models\Option;
use Livewire\Component;
use MicroweberPackages\ComposerClient\Client;
use MicroweberPackages\SharedServerScripts\MicroweberDownloader;
use MicroweberPackages\SharedServerScripts\MicroweberModuleConnectorsDownloader;
use MicroweberPackages\SharedServerScripts\MicroweberAppPathHelper;
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

        // The module connector must have own instance of composer client
        $composerClient = new Client();
        $composerClient->packageServers = [
            'https://market.microweberapi.com/packages/microweberserverpackages/packages.json'
        ];
        $downloader = new MicroweberModuleConnectorsDownloader();
        $downloader->setComposerClient($composerClient);
        $status = $downloader->download(config('whm-cpanel.sharedPaths.modules'));

        $composerClient = new Client();
        if (Option::getOption('license_key_status', 'whitelabel_license') == 'valid') {
            $composerClient->addLicense([
                'local_key' => Option::getOption('license_key', 'whitelabel_license')
            ]);
        }

        $downloader = new MicroweberTemplatesDownloader();
        $downloader->setComposerClient($composerClient);
        $status = $downloader->download(config('whm-cpanel.sharedPaths.templates'));

        $downloader = new MicroweberDownloader();

        $updateAppChannel = Option::getOption('update_app_channel','settings','stable');
        if ($updateAppChannel == 'stable') {
            $downloader->setReleaseSource(MicroweberDownloader::STABLE_RELEASE);
        } else {
            $downloader->setReleaseSource(MicroweberDownloader::DEV_RELEASE);
        }

        $downloader->setComposerClient($composerClient);
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
