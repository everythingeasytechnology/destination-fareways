<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSiteMaintenance
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = Setting::query()->first();

        if ($setting?->maintenance_mode === 'active') {
            return response()
                ->view('errors.maintenance', ['settings' => $setting], Response::HTTP_SERVICE_UNAVAILABLE)
                ->header('Retry-After', '3600');
        }

        return $next($request);
    }
}
