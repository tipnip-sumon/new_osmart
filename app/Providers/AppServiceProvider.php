<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
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
        // Set application locale based on session
        if (session('locale')) {
            App::setLocale(session('locale'));
        }
        
        // Register Order Observer for automatic volume tracking
        Order::observe(OrderObserver::class);
        
        // Register User Point Observer for automatic commission distribution
        \App\Models\User::observe(\App\Observers\UserPointObserver::class);
    }
}
