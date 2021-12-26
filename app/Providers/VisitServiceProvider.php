<?php

namespace App\Providers;

use App\Interfaces\VisitRepositoryInterface;
use App\Repositories\Visit\VisitRepository;
use Illuminate\Support\ServiceProvider;

class VisitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(VisitRepositoryInterface::class, VisitRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
