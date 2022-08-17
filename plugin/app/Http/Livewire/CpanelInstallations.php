<?php

namespace App\Http\Livewire;

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
        dispatch(new CpanelInstallationsScan());
        $this->emit('refreshInstallations');
    }

    public function confirmReinstallAll()
    {
        $this->confirmReinstallAll = true;
    }

    public function reinstallAll()
    {
        dispatch(new CpanelInstallationsReinstallAll());
        $this->emit('refreshInstallations');
        $this->confirmReinstallAll = false;
    }
}
