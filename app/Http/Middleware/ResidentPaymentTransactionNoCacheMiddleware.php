<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResidentPaymentTransactionNoCacheMiddleware
{
    /**
     * Prevent browsers from caching sensitive payment pages.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Payment pages should always be loaded fresh after payment or expiry.
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');

        return $response;
    }
}
