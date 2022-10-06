<?php

namespace App\Interfaces;

use App\Models\Rom;
use App\Models\RomFile;

interface RomQueriesInterface
{
    function formatRomSizeSQL(int $rom_size): string;

    function updateRomFromRomFileDataSQL(Rom $rom, RomFile $romFile): bool;

    function findRomMatchingRomFile(RomFile $romFile): ?Rom;

    function getCountOfRomsThatHaveROMFiles(): int;
}
