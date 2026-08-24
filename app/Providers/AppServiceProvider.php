<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\BloodSample;
use App\Observers\BloodSampleObserver;

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
        // Automatically activate the Sample History tracking observer
        BloodSample::observe(BloodSampleObserver::class);
    }
}