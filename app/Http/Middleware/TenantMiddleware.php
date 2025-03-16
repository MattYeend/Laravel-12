<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     *
     * @param Closure $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Tenant::where('domain', $request->getHost())->first();

        if ($tenant) {
            $tenant->makeCurrent();
        }

        return $next($request);
    }
}
