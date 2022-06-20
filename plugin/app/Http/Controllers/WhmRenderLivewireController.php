<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhmRenderLivewireController extends Controller
{
    public function render($params)
    {
        $componentName = $params['componentName'];
        $componentParams = $params['componentParams'];

        return view('whm.render-livewire', compact('componentName','componentParams'));
    }
}
