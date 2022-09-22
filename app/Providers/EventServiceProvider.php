<?php

namespace App\Providers;

use App\Events\RomFileCreated;
use App\Events\RomFileDeleting;
use App\Events\AttemptRomLinkToRomFile;
use App\Listeners\LinkRomToRomFile;
use App\Listeners\UnsetRomFileDataFromRom;
use App\Listeners\UpdateMatchingRom;
use App\Models\Game;
use App\Models\Rom;
use App\Models\User;
use App\Observers\GameObserver;
use App\Observers\RomObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        # Registered::class => [
        #     SendEmailVerificationNotification::class,
        # ],
        RomFileDeleting::class => [
            UnsetRomFileDataFromRom::class
        ],
        RomFileCreated::class => [
            UpdateMatchingRom::class
        ],
        AttemptRomLinkToRomFile::class => [
            LinkRomToRomFile::class
        ],
    ];

    /**
     * The model observers to register.
     *
     * @var string[][]
     */
    protected $observers = [
        User::class => [UserObserver::class],
        Game::class => [GameObserver::class],
        Rom::class => [RomObserver::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        Event::listen(
            Registered::class,
            [SendEmailVerificationNotification::class, 'handle']
        );
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
