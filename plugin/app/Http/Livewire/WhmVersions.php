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

        // Download core app
        $status = $this->__getMicroweberDownloaderInstance()
                    ->download(config('whm-cpanel.sharedPaths.app'));

        // Download modules
        $modulesDownloader = new MicroweberModuleConnectorsDownloader();
        $modulesDownloader->setComposerClient($this->__getComposerClientInstance());
        $status = $modulesDownloader->download(config('whm-cpanel.sharedPaths.modules'));

        // Download templates
        $templatesDownloader = new MicroweberTemplatesDownloader();
        $templatesDownloader->setComposerClient($this->__getComposerLicensedInstance());
        $status = $templatesDownloader->download(config('whm-cpanel.sharedPaths.templates'));
    }

    public function render()
    {

        $release = $this->__getMicroweberDownloaderInstance()->getRelease();

        $sharedPath = new MicroweberAppPathHelper();
        $sharedPath->setPath(config('whm-cpanel.sharedPaths.app'));

        $this->supportedLanguages = $sharedPath->getSupportedLanguages();
        $this->supportedTemplates = $sharedPath->getSupportedTemplates();
        $this->latestVersionOfApp = $release->getVersion();
        $this->currentVersionOfApp = $sharedPath->getCurrentVersion();
        $this->latestDownloadDateOfApp = $sharedPath->getCreatedAt();

        return view('livewire.whm.versions');
    }

    private function __getComposerClientInstance()
    {
        // The module connector must have own instance of composer client
        $composerClient = new Client();
        $composerClient->packageServers = [
            'https://market.microweberapi.com/packages/microweberserverpackages/packages.json'
        ];

        return $composerClient;
    }

    private function __getComposerLicensedInstance()
    {
        $composerClientLicensed = new Client();
        if (Option::getOption('license_key_status', 'whitelabel_license') == 'valid') {
            $composerClientLicensed->addLicense([
                'local_key' => Option::getOption('license_key', 'whitelabel_license')
            ]);
        }

        return $composerClientLicensed;
    }

    private function __getMicroweberDownloaderInstance()
    {
        $coreDownloader = new MicroweberDownloader();

        $updateAppChannel = Option::getOption('update_app_channel','settings','stable');
        if ($updateAppChannel == 'stable') {
            $coreDownloader->setReleaseSource(MicroweberDownloader::STABLE_RELEASE);
        } else {
            $coreDownloader->setReleaseSource(MicroweberDownloader::DEV_RELEASE);
        }

        $coreDownloader->setComposerClient($this->__getComposerClientInstance());

        return $coreDownloader;
    }
}
