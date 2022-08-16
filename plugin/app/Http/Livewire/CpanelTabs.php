<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CpanelTabs extends Component
{
    public $component = 'cpanel-installations';

    public function loadComponent($component)
    {
        $this->component = $component;
    }

    public function render()
    {
        return view('livewire.cpanel.tabs');
    }
}
