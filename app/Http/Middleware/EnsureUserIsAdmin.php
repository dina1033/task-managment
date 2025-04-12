<?php

namespace App\Http\Middleware;

use App\Enums\UserType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('filament.admin.auth.*')) {
            return $next($request);
        }

        $user = $request->user('admin');

        if (!$user || $user->type->value  != 'admin') {
            auth()->logout();
            return redirect()->route('filament.admin.auth.login')
                ->with('error', 'Only administrators can access this panel.');
        }
        return $next($request);
    }
}
