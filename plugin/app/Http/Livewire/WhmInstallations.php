<?php

namespace App\Http\Livewire;

use App\Console\Commands\AppInstallationsSync;
use Livewire\Component;

class WhmInstallations extends Component
{
    public function render()
    {
        return view('livewire.whm.installations');
    }

    public function scan()
    {
        dispatch(new AppInstallationsSync());

        $this->emit('refreshInstallations');
    }
}
