<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

class InitializeTenancyForLivewire
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $centralDomains = config('tenancy.central_domains', ['127.0.0.1', 'localhost']);
        
        // Only initialize tenancy if the current host is a tenant subdomain/domain
        if (!in_array($host, $centralDomains)) {
            return app(InitializeTenancyByDomain::class)->handle($request, $next);
        }

        return $next($request);
    }
}
