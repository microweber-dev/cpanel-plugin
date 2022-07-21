<?php

namespace App\Http\Livewire;

use App\Cpanel\CpanelApi;
use App\Models\AppInstallation;
use App\Models\Option;
use Livewire\Component;
use MicroweberPackages\SharedServerScripts\MicroweberAppPathHelper;
use MicroweberPackages\SharedServerScripts\MicroweberInstallationsScanner;
use MicroweberPackages\SharedServerScripts\MicroweberInstaller;
use MicroweberPackages\SharedServerScripts\MicroweberWhmcsConnector;

class WhmInstall extends Component
{
    public $supportedTemplates = [];
    public $supportedLanguages = [];

    public $installationDomainName;
    public $installationDomainPath = '';
    public $installationLanguage = 'en';
    public $installationTemplate = false;
    public $installationType = 'symlinked';
    public $installationDatabaseDriver = 'sqlite';
    public $installationAdminEmail;
    public $installationAdminUsername;
    public $installationAdminPassword;

    public $domains = [];

    public function render()
    {

        if (!empty($this->installationDomainName)) {
            $this->installationAdminEmail = 'admin@' . $this->installationDomainName;

            if (empty($this->installationTemplate)) {

                $whmcsConnector = new MicroweberWhmcsConnector();
                $whmcsConnector->setUrl(Option::getOption('whmcs_url', 'settings'));
                $whmcsConnector->setDomainName($this->installationDomainName);
                $this->installationTemplate = $whmcsConnector->getSelectedTemplateFromWhmcsUser();

            }
        }

        return view('livewire.whm.install');
    }

    public function mount()
    {

        $cpanelApi = new CpanelApi();
        $this->domains = $cpanelApi->getAllDomains();

        $sharedPath = new MicroweberAppPathHelper();
        $sharedPath->setPath(config('whm-cpanel.sharedPaths.app'));

        $this->supportedLanguages = $sharedPath->getSupportedLanguages();
        $this->supportedTemplates = $sharedPath->getSupportedTemplates();

        $this->installationLanguage = Option::getOption('installation_language', 'settings','en');;
        $this->installationTemplate = Option::getOption('installation_template', 'settings','new-world');;
        $this->installationType = Option::getOption('installation_type', 'settings','symlinked');
        $this->installationDatabaseDriver = Option::getOption('installation_database_driver', 'settings','sqlite');;
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
