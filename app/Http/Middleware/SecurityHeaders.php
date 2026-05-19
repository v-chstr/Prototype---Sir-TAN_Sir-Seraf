<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com data:",
            "img-src 'self' data: blob:",
            "connect-src 'self'",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);

        $headers = [
            'X-Frame-Options'        => 'DENY',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy'        => 'strict-origin-when-cross-origin',
            'Permissions-Policy'     => 'camera=(), microphone=(), geolocation=(), payment=()',
            'X-XSS-Protection'       => '0',
            'Content-Security-Policy' => $csp,
        ];

        if (app()->environment('production') && $request->isSecure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
        }

        foreach ($headers as $key => $value) {
            if (! $response->headers->has($key)) {
                $response->headers->set($key, $value);
            }
        }

        return $response;
    }
}
