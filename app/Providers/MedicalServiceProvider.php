<?php

namespace App\Providers;

use App\Interfaces\MedicalRepositoryInterface;
use App\Repositories\Medical\MedicalRepository;
use Illuminate\Support\ServiceProvider;

class MedicalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MedicalRepositoryInterface::class, MedicalRepository::class);
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
