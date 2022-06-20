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

Route::get('/installation/{id}',function ($id) {

    return app()->make(\App\Http\Controllers\WhmRenderLivewireController::class)->render([
        'componentName'=> 'whm-installation-view',
        'componentParams'=> ['id'=>$id],
    ]);

})->name('installation.view');

Route::any('/', function (\Illuminate\Http\Request $request) {

    $router = $request->get('router', false);

    if (!$router) {
        return app()->make(\App\Http\Controllers\WhmRenderLivewireController::class)->render([
            'componentName'=> 'whm-tabs',
            'componentParams'=> [],
        ]);
    }

    return \App\Http\RequestRoute::fireRouteRequest($router, $request);
});
