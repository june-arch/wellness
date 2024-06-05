<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    public function boot(): void
    {
        $this->rateLimiter();
        $this->routes(function () {
            $this->adminRoutes();
            $this->memberRoutes();
        });
    }

    protected function rateLimiter()
    {
        RateLimiter::for ('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    protected function adminRoutes()
    {
        Route::name('admin.')->prefix('api/admin')->middleware('web')->group(base_path('routes/admin.php'));
    }

    protected function memberRoutes()
    {
        Route::name('member.')->prefix('api/member')->middleware('api')->group(base_path('routes/member.php'));
    }
}
