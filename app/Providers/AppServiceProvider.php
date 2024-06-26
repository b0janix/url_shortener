<?php

namespace App\Providers;

use App\Services\Interfaces\SBClientInterface;
use App\Services\Interfaces\UrlModifyingInterface;
use App\Services\SBClientService;
use App\Services\UrlModifyingService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UrlModifyingInterface::class, fn() => new UrlModifyingService());
        $this->app->singleton(SBClientInterface::class, fn() => new SBClientService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
