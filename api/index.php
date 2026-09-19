<?php

$_ENV['LARAVEL_STORAGE_PATH'] = '/tmp/storage';
$_SERVER['LARAVEL_STORAGE_PATH'] = '/tmp/storage';
putenv('LARAVEL_STORAGE_PATH=/tmp/storage');

$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');

$_ENV['APP_PACKAGES_CACHE'] = '/tmp/storage/framework/cache/packages.php';
$_SERVER['APP_PACKAGES_CACHE'] = '/tmp/storage/framework/cache/packages.php';
putenv('APP_PACKAGES_CACHE=/tmp/storage/framework/cache/packages.php');

$_ENV['APP_SERVICES_CACHE'] = '/tmp/storage/framework/cache/services.php';
$_SERVER['APP_SERVICES_CACHE'] = '/tmp/storage/framework/cache/services.php';
putenv('APP_SERVICES_CACHE=/tmp/storage/framework/cache/services.php');

$_ENV['APP_CONFIG_CACHE'] = '/tmp/storage/framework/cache/config.php';
$_SERVER['APP_CONFIG_CACHE'] = '/tmp/storage/framework/cache/config.php';
putenv('APP_CONFIG_CACHE=/tmp/storage/framework/cache/config.php');

$_ENV['APP_ROUTES_CACHE'] = '/tmp/storage/framework/cache/routes.php';
$_SERVER['APP_ROUTES_CACHE'] = '/tmp/storage/framework/cache/routes.php';
putenv('APP_ROUTES_CACHE=/tmp/storage/framework/cache/routes.php');

$_ENV['APP_EVENTS_CACHE'] = '/tmp/storage/framework/cache/events.php';
$_SERVER['APP_EVENTS_CACHE'] = '/tmp/storage/framework/cache/events.php';
putenv('APP_EVENTS_CACHE=/tmp/storage/framework/cache/events.php');

if (! getenv('APP_MAINTENANCE_DRIVER') && empty($_ENV['APP_MAINTENANCE_DRIVER'])) {
    $_ENV['APP_MAINTENANCE_DRIVER'] = 'array';
    $_SERVER['APP_MAINTENANCE_DRIVER'] = 'array';
    putenv('APP_MAINTENANCE_DRIVER=array');
}

if (! getenv('SESSION_DRIVER') && empty($_ENV['SESSION_DRIVER'])) {
    $_ENV['SESSION_DRIVER'] = 'cookie';
    $_SERVER['SESSION_DRIVER'] = 'cookie';
    putenv('SESSION_DRIVER=cookie');
}

if (! getenv('CACHE_STORE') && empty($_ENV['CACHE_STORE'])) {
    $_ENV['CACHE_STORE'] = 'array';
    $_SERVER['CACHE_STORE'] = 'array';
    putenv('CACHE_STORE=array');
}

if (! getenv('LOG_CHANNEL') && empty($_ENV['LOG_CHANNEL'])) {
    $_ENV['LOG_CHANNEL'] = 'stderr';
    $_SERVER['LOG_CHANNEL'] = 'stderr';
    putenv('LOG_CHANNEL=stderr');
}

// Ensure storage subdirectories exist in /tmp for Vercel serverless environment
$storageDirs = [
    '/tmp/storage',
    '/tmp/storage/app',
    '/tmp/storage/app/public',
    '/tmp/storage/framework',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];

foreach ($storageDirs as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Forward request to Laravel public/index.php
require __DIR__.'/../public/index.php';
