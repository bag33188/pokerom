<?php

namespace App\Repositories;

use App\Interfaces\RomRepositoryInterface;
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
}
