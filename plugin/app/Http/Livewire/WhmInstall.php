<?php

namespace App\Http\Livewire;

use App\Cpanel\CpanelApi;
use App\Models\Option;
use Livewire\Component;
use MicroweberPackages\SharedServerScripts\MicroweberSharedPathHelper;
use MicroweberPackages\SharedServerScripts\MicroweberInstaller;

class WhmInstall extends Component
{
    public $supportedTemplates = [];
    public $supportedLanguages = [];

    public $installationDomainName;
    public $installationLanguage;
    public $installationTemplate;
    public $installationType;
    public $installationDatabaseDriver;
    public $installationAdminEmail;
    public $installationAdminUsername;
    public $installationAdminPassword;

    public $domains = [];

    public function render()
    {

        if (!empty($this->installationDomainName)) {
            $this->installationAdminEmail = 'admin@' . $this->installationDomainName;
        }

        return view('livewire.whm.install');
    }

    public function mount()
    {

        $cpanelApi = new CpanelApi();
        $this->domains = $cpanelApi->getAllDomains();

        $sharedPath = new MicroweberSharedPathHelper();
        $sharedPath->setPath(config('whm-cpanel.sharedPaths.app'));

        $this->supportedLanguages = $sharedPath->getSupportedLanguages();
        $this->supportedTemplates = $sharedPath->getSupportedTemplates();

    }

    public function install()
    {
        if (empty($this->installationDomainName)) {
            return;
        }

        $cpanelApi = new CpanelApi();
        $hostingAccounts = $cpanelApi->getHostingDetailsByDomainName($this->installationDomainName);
        if (!empty($hostingAccounts)) {

            $install = new MicroweberInstaller();
            $install->setPath($hostingAccounts['documentroot']);
            $install->setSourcePath(config('whm-cpanel.sharedPaths.app'));
            $install->setSymlinkInstallation();
            $install->setAdminUsername($this->installationAdminUsername);
            $install->setAdminPassword($this->installationAdminPassword);
            $install->setAdminEmail($this->installationAdminEmail);
            $run = $install->run();

            dd($run);

            file_put_contents($hostingAccounts['documentroot'] . "done.txt", rand(1, 9));
        }


    }
}
