<?php

namespace App\Http\Middleware;

use App\Enums\UserType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class EnsureUserIsUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('login') || $request->routeIs('register') || $request->routeIs('logout')) {
            return $next($request);
        }

        $user = $request->user('web');
        if (!$user || $user->type->value !== 'user') {
            return redirect()->route('login')
                ->with('error', 'Only Regular User can access this site.');
        }
        return $next($request);
    }
}
