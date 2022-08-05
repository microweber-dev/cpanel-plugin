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

        // Download core app
        $coreDownloader = new MicroweberDownloader();

        $updateAppChannel = Option::getOption('update_app_channel','settings','stable');
        if ($updateAppChannel == 'stable') {
            $coreDownloader->setReleaseSource(MicroweberDownloader::STABLE_RELEASE);
        } else {
            $coreDownloader->setReleaseSource(MicroweberDownloader::DEV_RELEASE);
        }

        $coreDownloader->setComposerClient($composerClient);
        $status = $coreDownloader->download(config('whm-cpanel.sharedPaths.app'));

        // Download modules
        $modulesDownloader = new MicroweberModuleConnectorsDownloader();
        $modulesDownloader->setComposerClient($composerClient);
        $status = $modulesDownloader->download(config('whm-cpanel.sharedPaths.modules'));

        // Download templates
        $composerClientLicensed = new Client();
        if (Option::getOption('license_key_status', 'whitelabel_license') == 'valid') {
            $composerClientLicensed->addLicense([
                'local_key' => Option::getOption('license_key', 'whitelabel_license')
            ]);
        }

        $templatesDownloader = new MicroweberTemplatesDownloader();
        $templatesDownloader->setComposerClient($composerClientLicensed);
        $status = $templatesDownloader->download(config('whm-cpanel.sharedPaths.templates'));
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
