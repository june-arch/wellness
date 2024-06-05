<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasCompanyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()->company) {
            throw new AuthorizationException();
        }

        return $next($request);
    }
}
