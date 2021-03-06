<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Visit;
use App\Policies\VisitPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Visit::class => VisitPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Bramka ograniczająca dostęp użytkownika typu lekarz do określonych zakładek
        Gate::define('admin-level', function (User $user) {
            if ($user->type_id === 1) {
                return Response::allow();
            }
            return  Response::deny('Musisz mieć uprawnienia administratora');
        });
    }
}
