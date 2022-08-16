<?php

use App\Console\Commands\AppInstallationsScan;
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

Route::get('/installation/{id}', function ($id) {

    return app()->make(\App\Http\Controllers\WhmRenderLivewireController::class)->render([
        'componentName' => 'whm-installation-view',
        'componentParams' => ['id' => $id],
    ]);

})->name('installation.view');

Route::get('/install', function (\Illuminate\Http\Request $request) {

    $runMigration = false;
    $dbFile = storage_path('database.sqlite');
    if (!is_file($dbFile)) {
        $runMigration = true;
    } else {
        if (empty(file_get_contents($dbFile))) {
            $runMigration = true;
        }
    }

    if ($runMigration) {
        \Artisan::call('migrate');
        dispatch(new AppInstallationsScan());
    }

});

Route::any('/', function (\Illuminate\Http\Request $request) {

    $router = $request->get('router', false);

    if (!$router) {
        if (defined('LARAVEL_CPANEL') && LARAVEL_CPANEL == true) {
            return app()->make(\App\Http\Controllers\CpanelRenderLivewireController::class)->render([
                'componentName' => 'whm-tabs',
                'componentParams' => [],
            ]);
        } else {
            return app()->make(\App\Http\Controllers\WhmRenderLivewireController::class)->render([
                'componentName' => 'whm-tabs',
                'componentParams' => [],
            ]);
        }
    }

    return \App\Http\RequestRoute::fireRouteRequest($router, $request);
});
