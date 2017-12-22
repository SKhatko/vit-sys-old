<?php namespace App\Http\Middleware;

use Closure;
use Session;

class AuthenticatePinClear
{


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Session::forget('auth.pin.auth_flag');
        return $next($request);

    }

}
