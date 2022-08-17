<?php

namespace App\Http\Livewire;

use App\Console\Commands\WhmInstallationsReinstallAll;
use App\Console\Commands\WhmInstallationsScan;
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
        dispatch(new WhmInstallationsScan());
        $this->emit('refreshInstallations');
    }

    public function confirmReinstallAll()
    {
        $this->confirmReinstallAll = true;
    }

    public function reinstallAll()
    {
        dispatch(new WhmInstallationsReinstallAll());
        $this->emit('refreshInstallations');
        $this->confirmReinstallAll = false;
    }
}
