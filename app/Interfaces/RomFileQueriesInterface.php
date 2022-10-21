<?php

namespace App\Interfaces;

interface RomFileQueriesInterface
{
    function getTotalSizeOfAllFilesThatHaveRoms(): int;

    function getListOfRomFilesInStorageDirectory(): array;

    function formatUploadDate(string $uploadDate, string $dateTimeFormat, string $timezone): string;
}
