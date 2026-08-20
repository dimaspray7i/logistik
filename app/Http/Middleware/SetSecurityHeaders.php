<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetSecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. Mencegah browser melakukan MIME-type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 2. Mencegah Clickjacking dengan membatasi embedding iframe
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 3. Mengontrol pengiriman informasi referer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 4. Membatasi akses fitur browser yang tidak diperlukan
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // 5. HSTS hanya dikirimkan jika koneksi aman (HTTPS) agar tidak merusak local HTTP development
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}