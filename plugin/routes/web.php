<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('/', function (\Illuminate\Http\Request $request) {

    $router = $request->get('router', false);
    if (!$router) {
        return app()->make(\App\Http\Controllers\WhmAdminController::class)->index($request);
    }

    return \App\Http\RequestRoute::fireRouteRequest($router, $request);

});


Route::get('/home',function () {
    echo 'o iee';
})->name('home');
