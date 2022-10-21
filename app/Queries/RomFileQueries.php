<?php

namespace App\Queries;

use App\Interfaces\RomFileQueriesInterface;
use App\Models\RomFile;
use Illuminate\Support\Facades\Storage;

class RomFileQueries implements RomFileQueriesInterface
{
    private readonly RomFile $romFile;

    public function __construct(RomFile $romFile)
    {
        $this->romFile = $romFile;
    }

    public function getTotalSizeOfAllFilesThatHaveRoms(): int
    {
        return $this->romFile->whereHas('rom')->sum('length');
    }

    public function getListOfRomFilesInStorageDirectory(): array
    {
        $storageRomFilesList = Storage::disk('public')->files(ROM_FILES_DIRNAME);
        $matchRomFilePattern = fn(string $romFilename): false|int => preg_match("/([\w\-_]+)\.(gb[ac]?|3ds|xci|nds)$/i", $romFilename);
        $removeStorageNameFromRomFilename = fn(string $romFilename): string => str_replace(sprintf("%s/", ROM_FILES_DIRNAME), '', $romFilename);

        $romFilesList = array_map(
            $removeStorageNameFromRomFilename,
            array_values(array_filter($storageRomFilesList, $matchRomFilePattern, ARRAY_FILTER_USE_BOTH))
        );

        $sortByStringLength = fn(string $a, string $b): int => strlen($a) <=> strlen($b);
        usort($romFilesList, $sortByStringLength);

        return $romFilesList;
    }
}
