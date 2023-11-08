<?php

namespace App\Providers;

use App\Services\Omitted\OmittedDocumentsService;
use App\Services\Omitted\OmittedDocumentsServiceInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OmittedDocumentsServiceInterface::class, OmittedDocumentsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
