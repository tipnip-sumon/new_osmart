<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Order;
use App\Observers\OrderObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Order Observer for automatic volume tracking
        Order::observe(OrderObserver::class);
        
        // Register User Point Observer for automatic commission distribution
        \App\Models\User::observe(\App\Observers\UserPointObserver::class);
    }
}
