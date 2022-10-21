<?php

namespace App\Queries;

use App\Interfaces\RomQueriesInterface;
use App\Models\Rom;
use App\Models\RomFile;
use Illuminate\Support\Facades\DB;
use Jenssegers\Mongodb\Helpers\EloquentBuilder;

class RomQueries implements RomQueriesInterface
{
    private readonly Rom $rom;

    public function __construct(Rom $rom)
    {
        $this->rom = $rom;
    }

    public function findRomMatchingRomFile(RomFile $romFile): ?Rom
    {
        list($romName, $romType) = explode('.', $romFile->filename, 2);

        // https://laravel.com/docs/9.x/queries#or-where-clauses
        return $this->rom
            ->where('rom_name', '=', $romName)              # AND
            ->where('rom_type', '=', $romType)              # AND
            ->where(function (EloquentBuilder $query) {                    # (
                $query
                    ->where('has_file', '=', FALSE)   # OR
                    ->orWhere('file_id', '=', NULL);  # )
            })->first();
    }

    public function formatRomSizeSQL(int $rom_size): string
    {
        $sql = /** @lang MariaDB */
            "SELECT HIGH_PRIORITY FORMAT_ROM_SIZE(?) AS `rom_size`";
        $query = DB::raw($sql);
        return DB::scalar($query, [$rom_size]);
    }

    public function updateRomFromRomFileDataSQL(Rom $rom, RomFile $romFile): bool
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

    public function getCountOfRomsThatHaveROMFiles(): int
    {
        return $this->rom->where('has_file', '=', TRUE)->count();
    }
}
