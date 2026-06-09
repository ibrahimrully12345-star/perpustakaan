<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        if (!in_array($userRole, $roles, true)) {
            return redirect($this->defaultRedirect($userRole));
        }

        return $next($request);
    }

    protected function defaultRedirect(string $role): string
    {
        if (in_array($role, ['admin', 'petugas'], true)) {
            return '/dashboard';
        }

        return '/katalog';
    }
}
