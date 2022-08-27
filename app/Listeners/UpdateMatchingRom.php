<?php

namespace App\Listeners;

use App\Events\RomFileCreated;
use App\Models\Rom;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateMatchingRom implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function shouldQueue(RomFileCreated $event): bool
    {
        list($romName, $romType) = explode('.', $event->romFile->filename, 2);
        $matchingRom = Rom::where('rom_name', '=', $romName, 'and')
            ->where('rom_type', '=', $romType, 'and')
            ->where(function ($query) {
                $query->where('has_file', false)
                    ->orWhere('file_id', null);
            })->first();
        return isset($matchingRom);
    }

    /**
     * Handle the event.
     *
     * @param RomFileCreated $event
     * @return void
     */
    public function handle(RomFileCreated $event)
    {
        //
    }
}
