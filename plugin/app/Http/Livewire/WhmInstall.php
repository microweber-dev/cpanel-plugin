<?php

namespace App\Http\Livewire;

use App\Cpanel\CpanelApi;
use App\Models\Option;
use Livewire\Component;
use MicroweberPackages\SharedServerScripts\MicroweberAppPathHelper;

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
        $accounts = $cpanelApi->execApi1('listaccts', array('search' => '', 'searchtype' => 'user'));

        if ($accounts and isset($accounts['data']) and isset($accounts['data']['acct'])) {
            foreach ($accounts['data']['acct'] as $account) {
                if (isset($account['user'])) {
                    $this->domains[] = $account;
                }
            }
        }

        $sharedPath = new MicroweberAppPathHelper();
        $sharedPath->setPath(config('whm-cpanel.sharedPaths.app'));

        $this->supportedLanguages = $sharedPath->getSupportedLanguages();
        $this->supportedTemplates = $sharedPath->getSupportedTemplates();

    }

    public function install()
    {

    }
}
