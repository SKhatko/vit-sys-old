<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Config;

class Authenticate
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }

        if (Config::get('database.connections.tenant.manager') && Config::get('database.connections.tenant.manager') === true && !$request->is('manager/*')) {
            return redirect('/manager');
        }

        $routePrefix = $request->route()->getPrefix();

        if (($routePrefix == '/restaurant' && !\App\Config::$has_restaurant) ||
            ($routePrefix == '/reception' && !\App\Config::$has_reception) ||
            ($routePrefix == '/analytics' && !\App\Config::$has_analytics) ||
            ($routePrefix == '/clients' && !\App\Config::$has_clients) ||
            ($routePrefix == '/admin' && !\App\Config::$has_admin)
        ) {

            return redirect('/');
        }

        return $next($request);
    }

}
