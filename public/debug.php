<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$app->instance('request', $request);
$app->bootstrap();

header('Content-Type: application/json');
echo json_encode([
    'request_host' => $request->getHost(),
    'config_central_domains' => config('tenancy.central_domains'),
    'env_central_domain' => env('CENTRAL_DOMAIN'),
    'app_url' => config('app.url'),
    'is_central' => in_array($request->getHost(), config('tenancy.central_domains', [])),
], JSON_PRETTY_PRINT);
