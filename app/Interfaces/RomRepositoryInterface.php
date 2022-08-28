<?php

namespace App\Interfaces;

interface RomRepositoryInterface
{
    function formatRomSizeSQL(int $rom_size);
}
