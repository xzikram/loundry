<?php

define('LARAVEL_START', microtime(true));

header('Content-Type: text/plain; charset=utf-8');

try {
    require __DIR__.'/../vendor/autoload.php';

    $app = require_once __DIR__.'/../bootstrap/app.php';

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $request = Illuminate\Http\Request::capture();
    $app->instance('request', $request);
    $app->bootstrap();

    echo "--- LARAVEL BOOTED SUCCESSFULLY ---\n\n";
    echo "Request Host: " . $request->getHost() . "\n";
    echo "Config Central Domains: " . print_r(config('tenancy.central_domains'), true) . "\n";
    echo "Env Central Domain: " . env('CENTRAL_DOMAIN') . "\n";
    echo "App URL: " . config('app.url') . "\n";
    echo "Is Central: " . (in_array($request->getHost(), config('tenancy.central_domains', [])) ? 'YES' : 'NO') . "\n";

} catch (\Throwable $e) {
    echo "--- FATAL ERROR DURING BOOT ---\n\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
