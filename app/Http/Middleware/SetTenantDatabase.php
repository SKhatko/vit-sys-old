<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Tenant;

use Illuminate\Http\Response;

class SetTenantDatabase
{
    /**
     * Sets the connection's database to the current user database
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!$request->secure() && env('APP_ENV') === 'prod') {
            return redirect()->secure($request->getRequestUri());
        }

        $parts = explode('.', $_SERVER["SERVER_NAME"]);
        $subdomain = $parts[0];

        $tenant = Tenant::active()->subdomain($subdomain)->first();
        if (!$tenant) {
            //return 'Subdomain not found';
            return new Response(view('home'));
            //Handle later (redirect to registration/website)
            //@TODO
        } else {
            //reset tenant connection
            DB::disconnect('tenant');

            Config::set('database.connections.tenant.host', $tenant->db_host);
            Config::set('database.connections.tenant.username', $tenant->db_username);
            Config::set('database.connections.tenant.password', $tenant->db_pass);
            Config::set('database.connections.tenant.database', $tenant->db_name);

            if ($tenant->admin) {
                Config::set('database.connections.tenant.manager', true);
            } else {
                Config::set('database.connections.tenant.manager', false);
            }

            \App\Config::load();

            if (!\App\Config::$language) {
                \App\Config::$language = $tenant->language;
            }

            \App::setLocale(\App\Config::$language);

            //set timezone
            $timezone = \App\Config::$timezone;
            date_default_timezone_set($timezone);


            //get enabled interfaces
            \App\Config::$has_reception = (boolean)$tenant->reception_enabled;
            \App\Config::$has_kitchen = (boolean)$tenant->kitchen_enabled;
            \App\Config::$has_restaurant = (boolean)$tenant->restaurant_enabled;
            \App\Config::$has_clients = (boolean)$tenant->clients_enabled;
            \App\Config::$has_analytics = (boolean)$tenant->analytics_enabled;
            \App\Config::$has_admin = (boolean)$tenant->admin_enabled;


            return $next($request)->header('P3P', 'CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

            //return $next($request);
        }

    }
}