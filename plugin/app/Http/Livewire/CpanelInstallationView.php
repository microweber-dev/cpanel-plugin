<?php

namespace App\Http\Livewire;

use App\Models\AppInstallation;
use Livewire\Component;
use MicroweberPackages\SharedServerScripts\MicroweberAppPathHelper;
use MicroweberPackages\SharedServerScripts\MicroweberReinstaller;
use MicroweberPackages\SharedServerScripts\MicroweberUninstaller;

class CpanelInstallationView extends Component
{
    public $appInstallation;
    public $reisntallType = '';
    public $reinstallMessage = '';
    public $confirmReinstall = false;
    public $showReinstallOptions = false;
    public $confirmUninstall = false;
    public $installedSuccess = false;
    public $confirmLoginAsAdmin = false;

    public function render()
    {
        return view('livewire.cpanel.view_installation');
    }

    public function mount($id)
    {
        $request = request();
        if ($request->get('installed_success') == 1) {
            $this->installedSuccess = true;
        }

        $findInstallation = AppInstallation::where('id', $id)->first();
        if ($findInstallation == null) {
            return $this->redirect(asset('') . 'microweber.live.php');
        }

        $this->appInstallation = $findInstallation;

        $this->reisntallType = 'standalone';
        if ($this->appInstallation->is_symlink == 1) {
            $this->reisntallType = 'symlink';
        }
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

    public function showReinstallOptions()
    {
        $this->showReinstallOptions = true;
    }

    public function confirmReinstall()
    {
        $this->confirmReinstall = true;
    }

    public function reinstall()
    {
        $sharedPath = new MicroweberAppPathHelper();
        $sharedPath->setPath(config('whm-cpanel.sharedPaths.app'));
        $currentVersion = $sharedPath->getCurrentVersion();

        $reInstall = new MicroweberReinstaller();
        $reInstall->setSourcePath(config('whm-cpanel.sharedPaths.app'));

        if ($this->reisntallType == 'symlink') {

            $reInstall->setSymlinkInstallation();

            $this->appInstallation->version = $currentVersion;
            $this->appInstallation->is_symlink = 1;
            $this->appInstallation->save();

        } else if ($this->reisntallType == 'standalone') {

            $reInstall->setStandaloneInstallation();

            $this->appInstallation->version = $currentVersion;
            $this->appInstallation->is_symlink = 0;
            $this->appInstallation->save();

        } else {
            $this->reinstallMessage = 'Please, select reinstall type.';
            return false;
        }

        $reInstall->setPath($this->appInstallation->path);
        $reInstall->run();

        $this->showReinstallOptions = false;
        $this->confirmUninstall = false;
    }

    public function confirmUninstall()
    {
        $this->confirmUninstall = true;
    }

    public function uninstall()
    {
        $uninstall = new MicroweberUninstaller();
        $uninstall->setPath($this->appInstallation->path);
        $status = $uninstall->run();

        $this->appInstallation->delete();

        return $this->redirect(asset('') . 'microweber.live.php');
    }
}
