<?php

namespace App\Providers;

use App\Http\Controllers\AuthController;
use App\Interfaces\AuthControllerInterface;
use App\Interfaces\LoggingServiceInterface;
use App\Interfaces\PlantServiceInterface;
use App\Interfaces\WeatherServiceInterface;
use App\Services\LoggingService;
use App\Services\PlantService;
use App\Services\PlantServiceLoggingDecorator;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthControllerInterface::class, AuthController::class);
        $this->app->singleton(WeatherServiceInterface::class, WeatherService::class);
        $this->app->singleton(PlantServiceInterface::class, PlantServiceLoggingDecorator::class);
        $this->app->singleton(LoggingServiceInterface::class, LoggingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
