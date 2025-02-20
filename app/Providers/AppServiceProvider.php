<?php

namespace App\Providers;

use App\Hashing\CustomHasher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

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
        Hash::extend('custom', function () {
            return new CustomHasher;
        });

        view()->composer('*', function ($view) {
            $view->with('authUser', request()->user());
        });
    }
}
