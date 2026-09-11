<?php

namespace App\Http\Middleware;

use App\Models\SecurityEvent;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Backend authorization guard for admin-only routes.
 * Register in bootstrap/app.php as ->alias(['admin' => EnsureUserIsAdmin::class])
 * and apply with ->middleware('admin') on admin route groups.
 * Frontend hiding of links/menus is NOT sufficient on its own — this middleware
 * is what actually protects the pages.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            SecurityEvent::record(
                $request->user()?->id,
                'unauthorized_access',
                "Attempted to access admin route: {$request->path()}"
            );

            abort(403, 'Unauthorized. Admin access only.');
        }

        return $next($request);
    }
}
