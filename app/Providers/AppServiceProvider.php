<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Register Brevo email transport (sends via HTTPS API, bypasses SMTP port blocks)
        Mail::extend('brevo', function (array $config = []) {
            return new \Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoApiTransport(
                $config['key'] ?? config('services.brevo.key') ?? env('BREVO_API_KEY')
            );
        });

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