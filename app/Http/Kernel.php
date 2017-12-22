<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            //\App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];


    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => 'App\Http\Middleware\Authenticate',
        'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
        'auth.pin' => 'App\Http\Middleware\AuthenticatePin',
        'auth.pin.clear' => 'App\Http\Middleware\AuthenticatePinClear',
        'auth.manager' => 'App\Http\Middleware\AuthenticateManager',
        'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'database' => 'App\Http\Middleware\SetTenantDatabase',
        'csrf' => 'App\Http\Middleware\VerifyCsrfToken',
        'online.language' => 'App\Http\Middleware\SetOnlineLanguage',
        'features.preorders' => 'App\Http\Middleware\PreordersFeature',
        'menu.url' => 'App\Http\Middleware\MenuUrl'
    ];

}
