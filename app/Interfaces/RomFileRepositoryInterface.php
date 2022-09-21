<?php

namespace App\Interfaces;

use App\Models\RomFile;

interface RomFileRepositoryInterface
{
    function uploadToGrid(string $romFilename): RomFile;

    function downloadFromGrid(RomFile $romFile): RomFile;

    function deleteFromGrid(RomFile $romFile): RomFile;

    function determineConsole(RomFile $romFile): string;

    function formatUploadDate(string $uploadDate, string $dtFormat, string $timezone): string;
}
