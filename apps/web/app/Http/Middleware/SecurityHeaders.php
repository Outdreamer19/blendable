<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Content Security Policy
        $isDevelopment = app()->environment('local', 'development');

        $scriptSrc = "'self' 'unsafe-inline' 'unsafe-eval' https://js.stripe.com https://checkout.stripe.com";
        $connectSrc = "'self' https://api.openai.com https://api.anthropic.com https://generativelanguage.googleapis.com https://checkout.stripe.com";
        
        // Add API base URL to connect-src if configured (for bypassing Cloudflare timeout)
        $apiBaseUrl = config('app.api_base_url');
        if ($apiBaseUrl) {
            $connectSrc .= ' ' . $apiBaseUrl;
        }

        // Add Vite dev server support in development
        if ($isDevelopment) {
            $scriptSrc .= ' http://localhost:5173 http://127.0.0.1:5173 http://localhost:5174 http://127.0.0.1:5174';
            $connectSrc .= ' http://localhost:5173 http://127.0.0.1:5173 http://localhost:5174 http://127.0.0.1:5174 ws://localhost:5173 ws://127.0.0.1:5173 ws://localhost:5174 ws://127.0.0.1:5174';
        }

        // Form action sources - allow submissions to Laravel server
        $formActionSrc = "'self'";
        if ($isDevelopment) {
            $formActionSrc .= ' http://127.0.0.1:8000 http://127.0.0.1:8001 http://127.0.0.1:8002';
        }

        $csp = "default-src 'self'; ".
               "script-src {$scriptSrc}; ".
               "script-src-elem {$scriptSrc}; ".
               "style-src 'self' 'unsafe-inline' https://fonts.bunny.net; ".
               "style-src-elem 'self' 'unsafe-inline' https://fonts.bunny.net; ".
               "img-src 'self' data: https:; ".
               "font-src 'self' data: https://fonts.bunny.net; ".
               "connect-src {$connectSrc}; ".
               'frame-src https://js.stripe.com; '.
               "object-src 'none'; ".
               "base-uri 'self'; ".
               "form-action {$formActionSrc};";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
