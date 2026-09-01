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

        if (! $this->app->runningInConsole()) {
            try {
                \App\Models\Reservation::whereIn('status', ['confirmed', 'in-play'])
                    ->where('end_time', '<=', \Carbon\Carbon::now())
                    ->update(['status' => 'completed']);
            } catch (\Exception $e) {
                // Ignore if database/table is not yet set up
            }
        }
    }
}