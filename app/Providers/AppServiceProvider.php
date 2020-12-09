<?php

namespace App\Providers;

use App\Http\Resources\LandImageResource;
use App\Http\Resources\LandResource;
use App\Http\Resources\ProductResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        LandResource::withoutWrapping();
        ProductResource::withoutWrapping();
        LandImageResource::withoutWrapping();
    }
}
