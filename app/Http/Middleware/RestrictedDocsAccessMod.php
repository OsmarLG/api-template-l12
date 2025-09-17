<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class RestrictedDocsAccessMod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }

    public function handle($request, \Closure $next)
    {
        return $next($request);
        
        if (app()->environment('local')) {
            return $next($request);
        }

        if (Gate::allows('viewApiDocs')) {
            return $next($request);
        }

        abort(403);
    }
}

