<?php namespace App\Http\Middleware;

use Closure;
use Session;

class PreordersFeature
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
        if (\App\Config::$has_kitchen) {
            return $next($request);
        }

        abort(404);
    }

}