<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Symfony\Component\HttpFoundation\Response;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $uinfo = Session::get('USER');
        // if (!$uinfo || empty($uinfo['ID']) || !isset($uinfo['logsessid'])) {
        //     return $next($request); // Redirect to login page or appropriate action
        // }

        // // Check if logsessid matches the stored SESSID
        // if ($uinfo['SESSID'] !== $uinfo['logsessid'] && empty($uinfo['AID'])) {
        //     Session::forget('USER');
        //     Session::forget('TSES');
        //     return redirect('/'); // Redirect to login page or appropriate action
        // }

        return $next($request);
    }
}
