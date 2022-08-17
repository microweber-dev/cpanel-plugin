<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

if (!function_exists('mkdir_recursive')) {
    function mkdir_recursive($pathname)
    {
        if ($pathname == '') {
            return false;
        }
        is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname));

        return is_dir($pathname) || @mkdir($pathname);
    }
}

if (defined('LARAVEL_CPANEL') && LARAVEL_CPANEL == true) {

    $storagePath = $_SERVER['TMPDIR'] . DIRECTORY_SEPARATOR . 'microweber-plugin/storage';
    if (!is_dir($storagePath)) {
        mkdir($storagePath);
    }

    $storageCachePaths = [
        '/app/public',
        '/framework/cache',
        '/framework/sessions',
        '/framework/testing',
        '/framework/views',
        '/logs',
    ];
    foreach ($storageCachePaths as $cachePath) {
        if (!is_dir($storagePath . $cachePath)) {
            mkdir_recursive($storagePath . $cachePath);
        }
    }

    $app->useStoragePath($storagePath);
}

return $app;
