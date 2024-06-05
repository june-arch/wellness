<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

class PermissionMiddleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $user = $request->user();

        if (!$user) {
            throw new AuthenticationException;
        }

        if (!$user->can($guards)) {
            throw new AuthorizationException;
        }

        return $next($request);
    }
}
