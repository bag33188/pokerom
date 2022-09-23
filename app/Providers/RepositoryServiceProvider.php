<?php

namespace App\Providers;

use App\Interfaces\RomFileRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\RomFileRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RomFileRepositoryInterface::class, RomFileRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        # $this->app->bindMethod([UserRepositoryInterface::class, 'getCurrentUserBearerToken'], fn($b, $app) => $b->getCurrentUserBearerToken($app->make(Request::class)));
        # $authToken = App::call([$this->userRepository, 'getCurrentUserBearerToken']);
    }
}
