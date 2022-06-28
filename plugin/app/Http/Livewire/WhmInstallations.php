<?php

namespace App\Http\Livewire;

use App\Console\Commands\AppInstallationsScan;
use Livewire\Component;

class WhmInstallations extends Component
{
    public function render()
    {
        return view('livewire.whm.installations');
    }

    public function scan()
    {
        dispatch(new AppInstallationsScan());
        $this->emit('refreshInstallations');
    }
}
