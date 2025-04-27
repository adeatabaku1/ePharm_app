<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\SubstituteBindings;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These run on *every* request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // Trust proxies (if you’re behind a load-balancer)
        \App\Http\Middleware\TrustProxies::class,

        // Handle CORS if your frontend is on another origin
        \Fruitcake\Cors\HandleCors::class,

        // Prevent requests during maintenance
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,

        // Trim whitespace on strings
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // (Only if you have web routes—can be left empty for pure-API apps)
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],

        'api' => [
            // Sanctum’s SPA/auth middleware (optional, but safe)
            EnsureFrontendRequestsAreStateful::class,

            // Rate limiting
            'throttle:api',

            // Route-model binding, etc.
            SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These may be assigned to routes or used in middleware groups.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // Laravel’s built-in
        'auth'             => \App\Http\Middleware\Authenticate::class,
        'auth.basic'       => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers'    => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can'              => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'            => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed'           => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle'         => ThrottleRequests::class,
        'verified'         => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,


        'auth.custom' => \App\Http\Middleware\Authenticate::class,
        'role'        => \App\Http\Middleware\RoleMiddleware::class,
    ];
}
