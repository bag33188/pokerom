<?php

namespace App\Listeners;

use App\Events\RomFileDeleting;
use App\Models\Rom;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UnsetRomFileDataFromRom implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = false;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function shouldQueue(RomFileDeleting $event): bool
    {
        return $event->romFile->rom()->exists();
    }

    /**
     * Handle the event.
     *
     * @param RomFileDeleting $event
     * @return void
     */
    public function handle(RomFileDeleting $event): void
    {
        Rom::withoutEvents(function () use ($event) {
            $rom = $event->romFile->rom()->first();
            $rom->has_file = FALSE;
            $rom->file_id = NULL;
            $rom->save();
        });
    }
}
