<?php

namespace App\Providers;

use App\Interfaces\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        /* $this->app->singleton(UserRepositoryInterface::class, function($app) {
            return new UserRepository (
                $app->make(User::class)
            );
        }); */

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Jeżeli skorzystamy z singleton a nie bind to obiekt nie będzie tworzony za każdym razem nowy, tylko po pierwszy
        // utworzeniu będzie przechowywany, a zwracana w przypadku wywołania będzie referencja
        /* $this->app->singleton(
            UserRepositoryInterface::class,
            UserRepository::class
        ); */
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
