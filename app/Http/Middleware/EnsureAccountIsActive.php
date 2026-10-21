<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    /**
     * Membatasi fitur keanggotaan hanya untuk akun yang sudah disetujui.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if (! $user->isActive()) {
            if ($request->expectsJson()) {
                abort(403, 'Akun Anda belum disetujui oleh admin.');
            }

            return redirect()
                ->route('account.status')
                ->with('info', 'Akun Anda belum aktif. Silakan tunggu persetujuan admin.');
        }

        return $next($request);
    }
}
