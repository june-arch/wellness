<?php

namespace App\Http\Middleware;

use App\Models\Users\Admin;
use App\Models\Users\Employee;
use App\Models\Users\Member;
use Closure;
use Illuminate\Auth\AuthenticationException;

class UserTypeMiddleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $user = $request->user();

        if (empty($guards) || $this->type($guards[0]) && !$user || $user->type !== $this->type($guards[0]) || $user->is_active === false) {
            throw new AuthenticationException();
        }

        return $next($request);
    }

    protected function type(string $guard)
    {
        if ($guard === 'admin') {
            return Admin::class;
        }

        if ($guard === 'employee') {
            return Employee::class;
        }

        if ($guard === 'member') {
            return Member::class;
        }
    }
}
