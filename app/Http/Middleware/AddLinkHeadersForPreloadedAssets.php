<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddLinkHeadersForPreloadedAssets
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (in_array($response->headers->get('Content-Type'), ['text/html', 'application/xhtml+xml'])) {
            $response->headers->set('Link', '', false);
        }

        return $response;
    }
}
