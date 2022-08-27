<?php

namespace App\Observers;

use App\Models\Rom;
use App\Models\RomFile;
use Illuminate\Support\Facades\DB;

class RomObserver
{
    public function created(Rom $rom): void
    {
        $romFile = RomFile::where('filename', $rom->getRomFileName())->first();
        if(isset($romFile)) {
            DB::statement("CALL spUpdateRomFromRomFileData(:rom_file_id, :rom_file_size, :rom_id);", [
                'rom_file_id' => $romFile->_id,
                'rom_file_size' => $romFile->length,
                'rom_id' => $rom->id,
            ]);
        }
    }
}
