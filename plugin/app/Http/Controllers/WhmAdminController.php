<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhmAdminController extends Controller
{
    public function index(Request $request)
    {
    //     dump($request);
        return view('welcome');
    }
}
