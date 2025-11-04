<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class TelescopeServiceProvider extends ServiceProvider
{
    /**
     * Check if Telescope is installed and available.
     */
    protected function isTelescopeAvailable(): bool
    {
        return class_exists('Laravel\Telescope\Telescope');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (! $this->isTelescopeAvailable()) {
            return;
        }

        // Telescope::night();

        $this->hideSensitiveRequestDetails();

        $isLocal = $this->app->environment('local');

        call_user_func(['Laravel\Telescope\Telescope', 'filter'], function ($entry) use ($isLocal) {
            return $isLocal ||
                   $entry->isReportableException() ||
                   $entry->isFailedRequest() ||
                   $entry->isFailedJob() ||
                   $entry->isScheduledTask() ||
                   $entry->hasMonitoredTag();
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if (! $this->isTelescopeAvailable()) {
            return;
        }

        if ($this->app->environment('local')) {
            return;
        }

        call_user_func(['Laravel\Telescope\Telescope', 'hideRequestParameters'], ['_token']);

        call_user_func(['Laravel\Telescope\Telescope', 'hideRequestHeaders'], [
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        if (! $this->isTelescopeAvailable()) {
            return;
        }

        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }
}
