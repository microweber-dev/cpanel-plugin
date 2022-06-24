<?php

namespace App\Http\Livewire;

use App\Cpanel\CpanelApi;
use App\Models\AppInstallation;
use Livewire\Component;
use MicroweberPackages\SharedServerScripts\MicroweberInstallationsScanner;
use MicroweberPackages\SharedServerScripts\MicroweberSharedPathHelper;
use MicroweberPackages\SharedServerScripts\MicroweberInstaller;

class WhmInstall extends Component
{
    public $supportedTemplates = [];
    public $supportedLanguages = [];

    public $installationDomainName;
    public $installationDomainPath = '';
    public $installationLanguage = 'en';
    public $installationTemplate = 'new-world';
    public $installationType = 'symlink';
    public $installationDatabaseDriver = 'sqlite';
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
        $hostingAccount = $cpanelApi->getHostingDetailsByDomainName($this->installationDomainName);
        if (!empty($hostingAccount)) {

            $dbPrefix = $cpanelApi->makeDbPrefixFromUsername($hostingAccount['user']);
            $dbPassword = $cpanelApi->randomPassword(12);
            $dbUsername = $dbName = $dbPrefix . 'mw'.date('mdHis');

            if ($this->installationDatabaseDriver == 'mysql') {
                $createDatabase = $cpanelApi->createDatabaseWithUser($hostingAccount['user'], $dbName, $dbUsername, $dbPassword);
                if (!$createDatabase) {
                    // Can't create database
                    return;
                }
            }


            $install = new MicroweberInstaller();
            $install->setChownUser($hostingAccount['user']);
            $install->enableChownAfterInstall();

            if (!empty($this->installationDomainPath)) {
                $this->installationDomainPath = '/' . $this->installationDomainPath;
            }

            $path = $hostingAccount['documentroot'] . $this->installationDomainPath;

            $install->setPath($path);
            $install->setSourcePath(config('whm-cpanel.sharedPaths.app'));

            $install->setLanguage($this->installationLanguage);
            $install->setTemplate($this->installationTemplate);

            if ($this->installationType == 'symlink') {
                $install->setSymlinkInstallation();
            } else {
                $install->setStandaloneInstallation();
            }

            $install->setDatabaseDriver($this->installationDatabaseDriver);

            if ($this->installationDatabaseDriver == 'mysql') {
                $install->setDatabaseUsername($dbUsername);
                $install->setDatabasePassword($dbPassword);
                $install->setDatabaseName($dbName);
            }

            $install->setAdminEmail($this->installationAdminEmail);
            $install->setAdminUsername($this->installationAdminUsername);
            $install->setAdminPassword($this->installationAdminPassword);

            $run = $install->run();

            $scanner = new MicroweberInstallationsScanner();
            $installation = $scanner->scanPath($path);

            if (!empty($installation)) {
                $installationId = AppInstallation::saveOrUpdateInstallation($hostingAccount, $installation);
                return $this->redirect(asset('/') . 'index.cgi?router=installation/' . $installationId.'&installed_success=1');
            }

            return $this->redirect(asset('/') . 'index.cgi');
        }


    }
}
