<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSystemIsOperational
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kunin ang estado ng system mula sa database (default ay '0' o Operational)[cite: 1]
        $systemState = SystemSetting::get('maintenance_mode', '0');

        // Kung ang state ay '0' (Operational), tuloy lang ang system[cite: 1]
        if ($systemState === '0') {
            return $next($request);
        }

        // Kung ang nag-a-access ay Admin, hayaan silang makapasok[cite: 1]
        if ($request->user() && $request->user()->isAdmin()) {
            return $next($request);
        }

        // Kung naka-maintenance o shutdown at hindi admin ang pumasok:
        if ($systemState === '1') {
            abort(503, 'PautangPro is currently undergoing scheduled system maintenance. Please check back shortly.');
        }

        if ($systemState === '2') {
            abort(503, 'PautangPro services are currently offline / shut down. Please contact the administrator for more information.');
        }

        return $next($request);
    }
}