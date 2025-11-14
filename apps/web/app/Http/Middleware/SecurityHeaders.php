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
        // Handle OPTIONS preflight requests for CORS
        $apiBaseUrl = config('app.api_base_url');
        if ($apiBaseUrl && $request->getHost() === parse_url($apiBaseUrl, PHP_URL_HOST) && $request->isMethod('OPTIONS')) {
            $origin = $request->headers->get('Origin');
            $allowedOrigins = [
                'https://blendable.app',
                'https://www.blendable.app',
            ];
            
            if (in_array($origin, $allowedOrigins)) {
                return response('', 204)
                    ->header('Access-Control-Allow-Origin', $origin)
                    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                    ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-CSRF-TOKEN, X-Requested-With, Accept')
                    ->header('Access-Control-Allow-Credentials', 'true')
                    ->header('Access-Control-Max-Age', '86400');
            }
        }

        $response = $next($request);

        // Add CORS headers for API subdomain requests (to allow cross-origin from main domain)
        if ($apiBaseUrl && $request->getHost() === parse_url($apiBaseUrl, PHP_URL_HOST)) {
            $origin = $request->headers->get('Origin');
            $allowedOrigins = [
                'https://blendable.app',
                'https://www.blendable.app',
            ];
            
            if (in_array($origin, $allowedOrigins)) {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-CSRF-TOKEN, X-Requested-With, Accept');
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
                $response->headers->set('Access-Control-Max-Age', '86400');
            }
        }

        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Content Security Policy
        $isDevelopment = app()->environment('local', 'development');

        $scriptSrc = "'self' 'unsafe-inline' 'unsafe-eval' https://js.stripe.com https://checkout.stripe.com https://static.cloudflareinsights.com";
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
