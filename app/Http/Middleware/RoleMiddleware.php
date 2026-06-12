<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (!auth()->user()->is_active) {
            auth()->logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => 'Akun Anda tidak aktif.',
                ]);
        }

        return $next($request);
    }
}
