<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- ADD THIS LINE

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Force HTTPS if the website is live
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}