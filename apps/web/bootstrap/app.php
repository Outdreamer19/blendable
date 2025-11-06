<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Rate limiting for API endpoints
        $middleware->throttleApi();

        // Add security headers
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Register plan enforcement middleware
        $middleware->alias([
            'enforce.plan.limits' => \App\Http\Middleware\EnforcePlanLimits::class,
            'ensure.workspace' => \App\Http\Middleware\EnsureWorkspace::class,
            'require.subscription' => \App\Http\Middleware\RequireSubscription::class,
        ]);

        // Exclude billing checkout and webhooks from CSRF protection
        $middleware->validateCsrfTokens(except: [
            'billing/checkout',
            'api/webhooks/stripe',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
