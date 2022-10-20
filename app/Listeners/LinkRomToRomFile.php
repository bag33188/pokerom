<?php

namespace App\Listeners;

use App\Actions\ApiMethodsTrait;
use App\Enums\FlashMessageTypeEnum;
use App\Events\AttemptRomLinkToRomFile;
use App\Interfaces\RomQueriesInterface;
use App\Models\RomFile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LinkRomToRomFile implements ShouldQueue
{
    use InteractsWithQueue;
    use ApiMethodsTrait {
        isApiRequest as private;
    }

    private static ?RomFile $matchingRomFile;

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

    public function shouldQueue(AttemptRomLinkToRomFile $event): bool
    {
        $romFile = RomFile::where('filename', $event->rom->getRomFileName())->first();

        $this->setMatchingRomFile($romFile);

        return !is_null($romFile) && $event->rom->has_file === FALSE;
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
        $this->romQueries->updateRomFromRomFileDataSQL($event->rom, self::$matchingRomFile);
        # $event->rom->refresh(); // cannot refresh ROM here, does not work
        $this->flashSuccessMessage();
    }

    private function flashSuccessMessage(): void
    {
        if (!$this->isApiRequest()) {
            session()->flash('message', 'Successfully linked ROM with matching ROM File ' . self::$matchingRomFile->filename);
            session()->flash('message-type', FlashMessageTypeEnum::SUCCESS);
        }
    }
}
