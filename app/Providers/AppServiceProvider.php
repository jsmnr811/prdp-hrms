<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        // Clear view cache in development to prevent caching issues on Windows
        if (app()->environment('local')) {
            $tempViewPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'laravel-views';
            if (is_dir($tempViewPath)) {
                $files = glob($tempViewPath . DIRECTORY_SEPARATOR . '*.php');
                foreach ($files as $file) {
                    @unlink($file);
                }
            }
        }

        Gate::define('viewVantage', function ($user = null) {

            return (($user && $user->hasRole('Super Administrator')) || ($user && $user->email == 'work.jasaure@gmail.com'));
        });
    }
}
