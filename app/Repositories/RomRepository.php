<?php

namespace App\Repositories;

use App\Interfaces\RomRepositoryInterface;
use App\Models\Rom;
use App\Models\RomFile;
use Illuminate\Support\Facades\DB;

class RomRepository implements RomRepositoryInterface
{
    public function formatRomSizeSQL(int $rom_size): string
    {
        $sql = /** @lang MariaDB */
            "SELECT HIGH_PRIORITY FORMAT_ROM_SIZE(?) AS `romSize`";
        $query = DB::raw($sql);
        return DB::selectOne($query, [$rom_size])->romSize;
    }

    function updateRomFromRomFileDataSQL(RomFile $romFile, Rom $rom): bool
    {
        $sql = /** @lang MariaDB */
            "CALL spUpdateRomFromRomFileData(:rom_file_id, :rom_file_size, :rom_id);";
        $query = DB::raw($sql);
        $stmt = DB::statement($query, [
            'rom_file_id' => $romFile->_id,
            'rom_file_size' => $romFile->length,
            'rom_id' => $rom->id,
        ]);
        return $stmt;
    }
}
