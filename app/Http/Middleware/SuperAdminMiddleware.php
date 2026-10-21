<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isActive() || ! $user->isSuperAdmin()) {
            abort(403, 'Hanya super admin yang dapat melakukan tindakan ini.');
        }

        return $next($request);
    }
}
