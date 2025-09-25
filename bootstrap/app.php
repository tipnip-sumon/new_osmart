<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
            
            Route::middleware('web')
                ->group(base_path('routes/vendor.php'));
                
            Route::middleware('web')
                ->group(base_path('routes/member.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register custom middleware
        $middleware->alias([
            'role.session' => \App\Http\Middleware\RoleSessionManager::class,
            'clear.cache.logout' => \App\Http\Middleware\ClearCacheOnLogout::class,
        ]);
        
        // Apply cache clearing middleware globally for logout routes
        $middleware->web(append: [
            \App\Http\Middleware\ClearCacheOnLogout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
