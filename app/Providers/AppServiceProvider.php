<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('manage-administrators', function ($user) {
            return $user->is_admin === true;
        });

        Gate::define('manage-institution-settings', function ($user) {
            return $user->is_admin === true;
        });
    }
}
