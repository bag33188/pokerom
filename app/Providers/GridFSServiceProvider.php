<?php

namespace App\Providers;

use App\Services\RomFileProcessor;
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
        $this->app->when(RomFileProcessor::class)
            ->needs(RomFilesConnection::class)
            ->give(function (Application $app) {
                return new RomFilesConnection($app->make(RomFilesDatabase::class, ['databaseName' => config('gridfs.connection.database'),
                    'bucketName' => config('gridfs.bucketName'),
                    'chunkSize' => config('gridfs.chunkSize')]));
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
        return [RomFilesConnection::class, RomFileProcessor::class];
    }
}
