<?php

/*
|--------------------------------------------------------------------------
| Laravel's Public Entry Point
|--------------------------------------------------------------------------
|
| Register the auto loader and create the application instance then run
| the application and send the response back to the client's browser.
|
*/

$__path = __DIR__;

// Fix Windows path issues
if (defined('PHP_WINDOWS_VERSION_BUILD')) {
    $__path = str_replace('\\', '/', $__path);
    $__path = preg_replace('/^([a-z]):/i', '', $__path);
}

define('LARAVEL_START', microtime(true));

// Register The Auto Loader
require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| Catch any errors that may be thrown and convert an error or exception
| into an Illuminate response, which may be sent back to the browsers.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| using the HTTP kernel. We'll then send the response back to
| this client's browser allowing them to enjoy our application.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
