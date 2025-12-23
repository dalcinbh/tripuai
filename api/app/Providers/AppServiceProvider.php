<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\TravelRequest;
use App\Observers\TravelRequestObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // TravelRequest::observe(TravelRequestObserver::class);
    }
}
