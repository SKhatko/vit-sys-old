<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
class AuthenticatePin
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

        if (!\App\Config::$pin) {
            return $next($request);
        }

        if (!Session::has('auth.pin.auth_flag') || time() - Session::get('auth.pin.auth_flag') >= 300) {
            Session::put('auth.pin.requested_url', $request->url());
            return redirect('/auth/pin');
        } else {
            Session::put('auth.pin.auth_flag', time());
            return $next($request);
        }
    }

}
