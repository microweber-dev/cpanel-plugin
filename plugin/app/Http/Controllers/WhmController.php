<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhmController extends Controller
{
    public function index(Request $request)
    {
        return view('whm.render-livewire');
    }
}