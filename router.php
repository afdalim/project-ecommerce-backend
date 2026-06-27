<?php

/*
|--------------------------------------------------------------------------
| Laravel Router for PHP Built-in Web Server
|--------------------------------------------------------------------------
|
| This file is used to route requests from the PHP built-in web server
| to the Laravel public/index.php file.
|
*/

$uri = $_SERVER['REQUEST_URI'];

// Check if the requested file exists as a real file or directory
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

// For everything else, route to Laravel's index.php
require_once __DIR__ . '/public/index.php';
