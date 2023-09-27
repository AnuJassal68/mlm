<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class AuthenticateDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
    //    dd(Session::has('user_id'));
        if (!Session::has('user_id')) {
            return redirect('log-in'); // Change 'log-in' to your actual login route
        }
       
       
        return $next($request);
    }
}
