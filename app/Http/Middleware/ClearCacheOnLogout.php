<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClearCacheOnLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Check if this is a logout request
        if ($this->isLogoutRequest($request)) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            $response->headers->set('Clear-Site-Data', '"cache", "cookies", "storage", "executionContexts"');
            
            // Add additional security headers
            $response->headers->set('X-Frame-Options', 'DENY');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('Referrer-Policy', 'no-referrer');
        }
        
        return $response;
    }
    
    /**
     * Determine if the current request is a logout request
     */
    private function isLogoutRequest(Request $request): bool
    {
        $uri = $request->getRequestUri();
        $method = $request->getMethod();
        
        return $method === 'POST' && (
            str_contains($uri, '/logout') ||
            str_contains($uri, '/member/logout') ||
            str_contains($uri, '/affiliate/logout') ||
            str_contains($uri, '/general/logout')
        );
    }
}