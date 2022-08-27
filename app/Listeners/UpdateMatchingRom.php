<?php

namespace App\Listeners;

use App\Events\RomFileCreated;
use App\Models\Rom;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateMatchingRom implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * Needs to be static since RomFile bucket connection is (a single) scoped singleton
     *
     * @var Rom|null
     */
    private static ?Rom $matchingRom;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * be sure to wrap in an instance method since this listener has multiple instances
     *
     * @param Rom|null $rom
     * @return void
     */
    private function setMatchingRom(?Rom $rom): void
    {
        self::$matchingRom = $rom;
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
        $this->setMatchingRom($matchingRom);
        return !$event->romFile->rom()->exists() && isset($matchingRom);
    }

    /**
     * Handle the event.
     *
     * @param RomFileCreated $event
     * @return void
     */
    public function handle(RomFileCreated $event): void
    {
        Rom::withoutEvents(function () use ($event) {
            if (isset(self::$matchingRom)) {
                self::$matchingRom->has_file = true;
                self::$matchingRom->file_id = $event->romFile->_id;
                self::$matchingRom->rom_size = $event->romFile->calculateRomSizeFromLength();
                self::$matchingRom->save();
            }
        });
    }
}
