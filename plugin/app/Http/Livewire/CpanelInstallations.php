<?php

namespace App\Http\Livewire;

use App\Console\Commands\AppInstallationsReinstallAll;
use App\Console\Commands\AppInstallationsScan;
use Livewire\Component;

class CpanelInstallations extends Component
{
    public $confirmReinstallAll = false;

    public function render()
    {
        return view('livewire.cpanel.installations');
    }

    public function scan()
    {
        dispatch(new AppInstallationsScan());
        $this->emit('refreshInstallations');
    }

    public function confirmReinstallAll()
    {
        $this->confirmReinstallAll = true;
    }

    public function reinstallAll()
    {
        dispatch(new AppInstallationsReinstallAll());
        $this->emit('refreshInstallations');
        $this->confirmReinstallAll = false;
    }
}
