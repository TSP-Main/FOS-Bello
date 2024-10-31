<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $page_name)
    {
        // Check if the user has permission for the requested page
        if (!view_permission($page_name)) {
            return redirect()->route('dashboard')->with('error', 'Access Denied!');
        }

        return $next($request);
    }
}
