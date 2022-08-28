<?php

namespace App\Interfaces;

use App\Models\RomFile;

interface RomFileRepositoryInterface
{
    function uploadToGrid(string $romFilename): RomFile;
    function downloadFromGrid(RomFile $romFile): RomFile;
    function deleteFromGrid(RomFile $romFile): RomFile;
}
