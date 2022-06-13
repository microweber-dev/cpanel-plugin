<?php

namespace App\Http\Livewire;

use Livewire\Component;

class WhmVersions extends Component
{
    public $currentVersionOfApp = 0;
    public $latestVersionOfApp = 0;
    public $latestDownloadDateOfApp = 0;
    public $availableTemplatesOfApp = [
        'new-world'=>[
            'name'=>'New World',
            'version'=>'1.2',
        ]
    ];

    public function render()
    {
        return view('livewire.whm.versions');
    }
}
