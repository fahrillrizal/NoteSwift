<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected $timeout = 1200;

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = Session::get('lastActivityTime');
            $currentTime = time();

            if ($lastActivity && ($currentTime - $lastActivity) > $this->timeout) {
                Auth::logout();
                Session::flush();
                return redirect()->route('login')->with('message', 'You have been logged out due to inactivity.');
            }

            Session::put('lastActivityTime', $currentTime);
        }

        return $next($request);
    }
}
