<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class CheckTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Tenant::where('domain', $request->getHost())->first();

        if ($tenant && auth()->check()) {
            // Make sure the authenticated user belongs to the tenant.
            if (auth()->user()->tenant_id !== $tenant->id) {
                // If the user does not belong to the tenant, deny access and redirect.
                return redirect()->route('home')->withErrors(['error' => 'You cannot access this tenant.']);
            }
        }

        return $next($request);
    }
}
