<?php

namespace App\Listeners;

use App\Events\AttemptRomLinkToRomFile;
use App\Interfaces\RomRepositoryInterface;
use App\Models\RomFile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class LinkRomToRomFile implements ShouldQueue
{
    use InteractsWithQueue;

    private static ?RomFile $matchingRomFile;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private readonly RomFile $romFile, private readonly RomRepositoryInterface $romRepository)
    {
        //
    }

    public function shouldQueue(AttemptRomLinkToRomFile $event): bool
    {
        $romFile = $this->romFile->where('filename', $event->rom->getRomFileName())->first();

        $this->setMatchingRomFile($romFile);

        return isset($romFile) && $event->rom->has_file === FALSE;
    }

    private function setMatchingRomFile(?RomFile $romFile): void
    {
        self::$matchingRomFile = $romFile;
    }

    /**
     * Handle the event.
     *
     * @param AttemptRomLinkToRomFile $event
     * @return void
     */
    public function handle(AttemptRomLinkToRomFile $event): void
    {
        $this->romRepository->updateRomFromRomFileDataSQL($event->rom, self::$matchingRomFile);
    }
}
