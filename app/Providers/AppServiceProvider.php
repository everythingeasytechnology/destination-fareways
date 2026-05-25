<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\CallSetting;

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
        // Inject settings globally into all frontend views
        View::composer('layouts.frontend', function($view) {
            $view->with('settings', Setting::first());
            $view->with('callSettings', CallSetting::first());
        });
    }
}
