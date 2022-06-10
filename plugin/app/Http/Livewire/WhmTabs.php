<?php

namespace App\Http\Livewire;

use Livewire\Component;

class WhmTabs extends Component
{
    public $component;

    public function loadComponent($component)
    {
        $this->component = $component;
    }

    public function render()
    {
        return view('livewire.whm.tabs');
    }
}
