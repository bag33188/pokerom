<?php

namespace App\Listeners;

use App\Actions\ApiMethodsTrait;
use App\Enums\FlashMessageTypeEnum;
use App\Events\RomFileCreated;
use App\Interfaces\RomQueriesInterface;
use App\Models\Rom;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateMatchingRom implements ShouldQueue
{
    use InteractsWithQueue;
    use ApiMethodsTrait {
        isApiRequest as private;
    }

    // make sure ROM file has been successfully uploaded and
    // stored in the grid (mongodb transaction) before updating ROM
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
        return $event->romFile->rom()->exists() == false && is_null($matchingRom) == false;
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
            self::$matchingRom->has_file = true;
            self::$matchingRom->file_id = $event->romFile->_id;
            self::$matchingRom->rom_size = $event->romFile->calculateRomSizeFromLength();
            self::$matchingRom->save();
            $this->flashSuccessMessage();
        });
    }

    private function flashSuccessMessage(): void
    {
        if (!$this->isApiRequest()) {
            session()->flash('message', 'Successfully updated matching ROM ' . self::$matchingRom->rom_name);
            session()->flash('message-type', FlashMessageTypeEnum::SUCCESS);
        }
    }
}
