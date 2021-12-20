<?php

namespace App\Providers;

use App\Interfaces\AdditionalServiceRepositoryInterface;
use App\Repositories\AdditionalService\AdditionalServiceRepository;
use Illuminate\Support\ServiceProvider;

class AdditionalServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AdditionalServiceRepositoryInterface::class, AdditionalServiceRepository::class);
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
