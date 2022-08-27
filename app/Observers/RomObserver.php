<?php

namespace App\Observers;

use App\Models\Rom;
use App\Models\RomFile;
use Illuminate\Support\Facades\DB;

class RomObserver
{
    private bool $useDbLogic = true;

    public function created(Rom $rom): void
    {
        $romFile = RomFile::where('filename', $rom->getRomFileName())->first();
        if (isset($romFile)) {
            $sql = /** @lang MariaDB */
                "CALL spUpdateRomFromRomFileData(:rom_file_id, :rom_file_size, :rom_id);";
            DB::statement($sql, [
                'rom_file_id' => $romFile->_id,
                'rom_file_size' => $romFile->length,
                'rom_id' => $rom->id,
            ]);
        }
    }

    public function updated(Rom $rom): void
    {
        if ($rom->has_file === FALSE) {
            $romFile = RomFile::where('filename', $rom->getRomFileName())->first();
            if (isset($romFile)) {
                $sql = /** @lang MariaDB */
                    "CALL spUpdateRomFromRomFileData(:rom_file_id, :rom_file_size, :rom_id);";
                DB::statement($sql, [
                    'rom_file_id' => $romFile->_id,
                    'rom_file_size' => $romFile->length,
                    'rom_id' => $rom->id,
                ]);
            }
        }
    }

    public function deleting(Rom $rom): void
    {
        // for unique constraint purposes
        $rom->file_id = NULL;
    }

    public function deleted(Rom $rom): void
    {
        if ($this->useDbLogic === false) {
            $rom->game()->delete();
        }
    }
}
