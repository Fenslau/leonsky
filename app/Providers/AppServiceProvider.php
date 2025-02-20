<?php

namespace App\Providers;

use App\Hashing\CustomHasher;
use App\Rules\CustomPasswordRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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

        Password::defaults(function () {
            return Password::min(4)
                ->rules([new CustomPasswordRule()]);
        });

        view()->composer('*', function ($view) {
            $view->with('authUser', request()->user());
        });
    }
}
