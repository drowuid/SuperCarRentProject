<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

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
    public function boot()
{
    // Register your custom middleware
    Route::aliasMiddleware('admin', AdminMiddleware::class);
}
}
