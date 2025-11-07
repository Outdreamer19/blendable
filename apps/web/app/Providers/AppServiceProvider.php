<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
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
        Vite::prefetch(concurrency: 3);
        
        // Register UsageLedger observer
        \App\Models\UsageLedger::observe(\App\Observers\UsageLedgerObserver::class);
        
        // Ensure secure cookies are used when HTTPS is detected
        // This fixes CSRF token issues when SESSION_SECURE_COOKIE is not set in .env
        // Laravel will auto-detect HTTPS if SESSION_SECURE_COOKIE is null, but if it's
        // explicitly false, we need to override it when HTTPS is detected
        if ($this->app->runningInConsole() === false && request()->secure()) {
            $currentSecure = $this->app['config']->get('session.secure');
            // Only override if not explicitly set to true
            if ($currentSecure !== true) {
                $this->app['config']->set('session.secure', true);
            }
        }
    }
}
