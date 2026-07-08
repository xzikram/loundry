<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\TenantStatus;

class EnsureSubscriptionValid
{
    public function handle(Request $request, Closure $next): Response
    {
        // If tenancy is not initialized, proceed (running on central domain)
        if (! tenant()) {
            return $next($request);
        }

        $status = tenant('status');

        if ($status === TenantStatus::SUSPENDED->value) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your account has been suspended. Please contact support.'], 403);
            }
            abort(403, 'Your account has been suspended. Please contact support.');
        }

        if ($status === TenantStatus::EXPIRED->value) {
            // Allow accessing billing or logout routes if needed, otherwise block
            if ($request->is('billing*') || $request->is('logout')) {
                return $next($request);
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Subscription expired. Please renew your plan.'], 402);
            }

            // In real app, redirect to billing page. For now, abort with 402.
            abort(402, 'Subscription expired. Please renew your plan.');
        }

        // Handle trial checking
        if ($status === TenantStatus::TRIAL->value) {
            $trialEnds = tenant('trial_ends_at');
            if ($trialEnds && now()->gt($trialEnds)) {
                // Set tenant status to expired dynamically and save
                tenant()->update(['status' => TenantStatus::EXPIRED->value]);

                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Trial period expired. Please upgrade.'], 402);
                }
                abort(402, 'Trial period expired. Please upgrade.');
            }
        }

        return $next($request);
    }
}
