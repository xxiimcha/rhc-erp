<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Maintenance mode file path (adjusted to laravel/)
if (file_exists($maintenance = __DIR__.'/../laravel/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Composer autoload (adjusted path)
require __DIR__.'/../laravel/vendor/autoload.php';

// Bootstrap Laravel and handle request (adjusted path)
/** @var Application $app */
$app = require_once __DIR__.'/../laravel/bootstrap/app.php';

$app->handleRequest(Request::capture());
