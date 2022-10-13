<?php

namespace App\Listeners;

use App\Events\RomFileCreated;
use App\Interfaces\RomQueriesInterface;
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
     * @param RomQueriesInterface $romQueries
     * @return void
     */
    public function __construct(private readonly RomQueriesInterface $romQueries)
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
        $matchingRom = $this->romQueries->findRomMatchingRomFile($event->romFile);

        $this->setMatchingRom($matchingRom);

        return !($event->romFile->rom()->exists() && is_null($matchingRom));
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
            $matchingRomIsNull = is_null(self::$matchingRom);
            if (!$matchingRomIsNull) {
                self::$matchingRom->has_file = true;
                self::$matchingRom->file_id = $event->romFile->_id;
                self::$matchingRom->rom_size = $event->romFile->calculateRomSizeFromLength();
                self::$matchingRom->save();
            }
        });
    }
}
