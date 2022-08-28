<?php

namespace App\Queries;

use App\Interfaces\RomQueriesInterface;
use App\Models\Rom;
use App\Models\RomFile;
use Illuminate\Support\Facades\DB;

class RomQueries implements RomQueriesInterface
{
    public function __construct(private readonly Rom $rom)
    {
    }

    public function findRomMatchingRomFile(RomFile $romFile): ?Rom
    {
        list($romName, $romType) = explode('.', $romFile->filename, 2);

        return $this->rom
            ->where('rom_name', '=', $romName, 'and')
            ->where('rom_type', '=', $romType, 'and')
            ->where(function ($query) {
                $query
                    ->where('has_file', '=', FALSE)
                    ->orWhere('file_id', '=', NULL);
            })->first();
    }

    public function formatRomSizeSQL(int $rom_size): string
    {
        $sql = /** @lang MariaDB */
            "SELECT HIGH_PRIORITY FORMAT_ROM_SIZE(?) AS `romSize`";
        $query = DB::raw($sql);
        return DB::selectOne($query, [$rom_size])->romSize;
    }

    function updateRomFromRomFileDataSQL(Rom $rom, RomFile $romFile): bool
    {
        $sql = /** @lang MariaDB */
            "CALL spUpdateRomFromRomFileData(:rom_file_id, :rom_file_size, :rom_id);";
        $query = DB::raw($sql);
        return DB::statement($query, [
            'rom_file_id' => $romFile->_id,
            'rom_file_size' => $romFile->length,
            'rom_id' => $rom->id,
        ]);
    }
}
