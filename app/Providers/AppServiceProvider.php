<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(fn ($user) => $user->is_super_admin ? true : null);

        RateLimiter::for('inquiries', fn (Request $request) => [
            Limit::perMinute(3)->by($request->ip()),
            Limit::perDay(10)->by(strtolower((string) $request->input('email')).'|'.$request->ip()),
        ]);
    }
}
