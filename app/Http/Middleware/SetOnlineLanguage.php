<?php namespace App\Http\Middleware;

use Closure;
use Session;

class SetOnlineLanguage
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
        $language = Session::has('online.language') ? Session::get('online.language') : \App\Config::$language;
        \App::setLocale($language);

        return $next($request);
    }

}
