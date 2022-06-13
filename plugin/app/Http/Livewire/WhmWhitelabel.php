<?php

namespace App\Http\Livewire;

use App\Models\Option;
use Livewire\Component;

class WhmWhitelabel extends Component
{
    public $state = [];

    public function render()
    {
        if (!empty($this->state)) {
            foreach ($this->state as $key=>$value) {
                Option::updateOption($key, $value);
            }
        }

        return view('livewire.whm.whitelabel');
    }

    public function mount()
    {
        // mount state
        $this->state = array_merge($this->state, Option::getAll());
    }
}
