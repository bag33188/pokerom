<?php

namespace App\Providers;

use App\Services\RomFilesConnection;
use App\Services\RomFilesDatabase;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;


class GridFSServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        # // https://laravel.com/docs/9.x/container#binding-scoped
        # $this->app->scoped(RomFilesConnection::class, function (Application $app) {
        #     return new RomFilesConnection($app->make(RomFilesDatabase::class));
        # });
        # // use singleton?? better performance since scoped gets flushed after queue worker gets processed??

        $this->app->singleton(RomFilesConnection::class, function (Application $app) {
            return new RomFilesConnection($app->make(RomFilesDatabase::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * _Does not bootstrap until service provider is needed._
     *
     * @return string[]
     */
    public function provides(): array
    {
        return [RomFilesConnection::class];
    }
}
