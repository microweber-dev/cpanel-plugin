<?php

namespace App\Http\Livewire;

use App\Models\AppInstallation;
use Livewire\Component;
use MicroweberPackages\SharedServerScripts\MicroweberAppPathHelper;
use MicroweberPackages\SharedServerScripts\MicroweberReinstaller;
use MicroweberPackages\SharedServerScripts\MicroweberUninstaller;

class WhmInstallationView extends Component
{
    public $appInstallation;
    public $confirmUninstall = false;
    public $installedSuccess = false;
    public $confirmLoginAsAdmin = false;

    public function render()
    {
        return view('livewire.whm.view_installation');
    }

    public function mount($id)
    {
        $request = request();
        if ($request->get('installed_success') == 1) {
            $this->installedSuccess = true;
        }

        $findInstallation = AppInstallation::where('id', $id)->first();
        if ($findInstallation == null) {
            return $this->redirect(asset('') . 'index.cgi');
        }

        $this->appInstallation = $findInstallation;
    }

    public function update()
    {

    }

    public function loginAsAdmin()
    {
        $appPathHelper = new MicroweberAppPathHelper();
        $appPathHelper->setPath($this->appInstallation->path);
        $token = $appPathHelper->generateAdminLoginToken();
        if ($token) {
            $this->confirmLoginAsAdmin = $this->appInstallation->url .'/api/user_login?secret_key='. $token;
        }
    }

    public function confirmUninstall()
    {
        $this->confirmUninstall = true;
    }

    public function reinstall()
    {
        $reInstall = new MicroweberReinstaller();
        $reInstall->setSourcePath(config('whm-cpanel.sharedPaths.app'));

        if ($this->appInstallation->is_symlink == 1) {
            $reInstall->setSymlinkInstallation();
        } else {
            $reInstall->setStandaloneInstallation();
        }

        $reInstall->setPath($this->appInstallation->path);
        $reInstall->run();
    }

    public function uninstall()
    {
        $uninstall = new MicroweberUninstaller();
        $uninstall->setPath($this->appInstallation->path);
        $status = $uninstall->run();

        $this->appInstallation->delete();

        return $this->redirect(asset('') . 'index.cgi');
    }
}
