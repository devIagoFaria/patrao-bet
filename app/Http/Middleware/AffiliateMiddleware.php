<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class AffiliateMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $refId = intval($request->query('ref'));
            
            // Check if referrer user exists to prevent fake/dead cookie values
            if ($refId > 0 && User::where('id', $refId)->exists()) {
                // Queue the cookie for 30 days (43200 minutes)
                Cookie::queue('fone777_affiliate_ref', $refId, 43200);
            }
        }

        return $next($request);
    }
}
