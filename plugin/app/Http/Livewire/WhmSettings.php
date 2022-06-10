<?php

namespace App\Http\Livewire;

use App\Models\Option;
use Livewire\Component;

class WhmSettings extends Component
{
    public $state = [
        'installation_type'=>'default'
    ];

    public function render()
    {
        if (!empty($this->state)) {
            foreach ($this->state as $key=>$value) {
                Option::updateOption($key, $value);
            }
        }

        return view('livewire.whm.settings');
    }

    public function mount()
    {
       // mount state
        $this->state = array_merge($this->state, Option::getAll());
    }
}
