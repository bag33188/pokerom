<?php

namespace App\Providers;

use App\Models\Game;
use App\Models\Rom;
use App\Models\RomFile;
use App\Models\User;
use App\Policies\GamePolicy;
use App\Policies\RomFilePolicy;
use App\Policies\RomPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Game::class => GamePolicy::class,
        Rom::class => RomPolicy::class,
        RomFile::class => RomFilePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('view-currentUserData', fn(User $user, User $model): bool => $user->id === $model->id);

        // give admin user complete access to all endpoints and actions
        Gate::before(function (User $user, string $ability) {
            # dd($ability); ddd($ability);
            if ($user->isAdmin()) {
                return true;
            }
        });
    }
}
