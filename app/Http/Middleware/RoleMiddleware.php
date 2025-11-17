<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  array|string  $roles  contoh: 'admin' atau ['admin','kader']
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        // kalau role user tidak ada di daftar roles, tolak
        if (! in_array($user->role, $roles)) {
            abort(403, 'Anda tidak punya akses.');
        }

        return $next($request);
    }
}
    