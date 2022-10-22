<?php

namespace App\Queries;

use App\Interfaces\RomFileQueriesInterface;
use App\Models\RomFile;
use DateTime;
use DateTimeZone;
use Exception;
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
        $matchRomFilePattern = fn(string $romFilename): false|int => preg_match(str_replace('/^', '/', ROM_FILENAME_PATTERN), $romFilename);
        $removeStorageNameFromRomFilename = fn(string $romFilename): string => str_replace(sprintf("%s/", ROM_FILES_DIRNAME), '', $romFilename);

        $romFilesList = array_map(
            $removeStorageNameFromRomFilename,
            array_values(array_filter($storageRomFilesList, $matchRomFilePattern, mode: ARRAY_FILTER_USE_BOTH))
        );

        $sortByStringLength = fn(string $a, string $b): int => strlen($a) <=> strlen($b);
        usort($romFilesList, $sortByStringLength);

        return $romFilesList;
    }

    public function formatUploadDate(string $uploadDate, string $dateTimeFormat, string $timezone): string
    {
        try {
            $uploadDateTime = new DateTime($uploadDate);
            $uploadTimeZone = new DateTimeZone($timezone);
            return $uploadDateTime->setTimezone($uploadTimeZone)->format($dateTimeFormat);
        } catch (Exception $e) {
            return $e->getMessage() ?? 'Invalid date/datetime';
        }
    }
}
