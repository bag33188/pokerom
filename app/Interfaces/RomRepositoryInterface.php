<?php

namespace App\Interfaces;

use App\Models\Rom;
use App\Models\RomFile;

interface RomRepositoryInterface
{
    function formatRomSizeSQL(int $rom_size): string;
    function updateRomFromRomFileDataSQL(RomFile $romFile, Rom $rom): bool;
}
