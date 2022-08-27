<?php

namespace App\Listeners;

use App\Events\RomProcessed;
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
    public function __construct()
    {
        //
    }

    public function shouldQueue(RomProcessed $event): bool
    {
        $romFile = RomFile::where('filename', $event->rom->getRomFileName())->first();
        $this->setMatchingRomFile($romFile);
        return $romFile->exists();
    }

    private function setMatchingRomFile(?RomFile $romFile): void
    {
        self::$matchingRomFile = $romFile;
    }

    /**
     * Handle the event.
     *
     * @param RomProcessed $event
     * @return void
     */
    public function handle(RomProcessed $event): void
    {
        $sql = /** @lang MariaDB */
            "CALL spUpdateRomFromRomFileData(:rom_file_id, :rom_file_size, :rom_id);";
        DB::statement($sql, [
            'rom_file_id' => self::$matchingRomFile->_id,
            'rom_file_size' => self::$matchingRomFile->length,
            'rom_id' => $event->rom->id,
        ]);
    }
}
