<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class MenuUrl
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
        $menuUrlStatus = DB::connection('tenant')->table('menu_config')->select('url_activated')->get()->first();
        if ($menuUrlStatus->url_activated) {
            return $next($request);
        }
        abort(404);
    }
}
