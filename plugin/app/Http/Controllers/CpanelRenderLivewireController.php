<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CpanelRenderLivewireController extends Controller
{
    public function render($params)
    {
        $componentName = $params['componentName'];
        $componentParams = $params['componentParams'];

        return view('cpanel.render-livewire', compact('componentName','componentParams'));
    }
}
