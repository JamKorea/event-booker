<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganiser
{
    /**
     * Handle an incoming request.
     *
     * Ensure only users with the "organiser" role can proceed.
     * If the user is not an organiser, return 403 Forbidden.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->role !== 'organiser') {
            abort(403, 'Unauthorized action. Only organisers can access this page.');
        }

        return $next($request);
    }
}