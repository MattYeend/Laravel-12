<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenant
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

        if ($tenant && auth()->check()) {
            if (auth()->user()->tenant_id !== $tenant->id) {
                return redirect()
                    ->route('home')
                    ->withErrors(['error' => 'You cannot access this tenant.']);
            }
        }

        return $next($request);
    }
}
