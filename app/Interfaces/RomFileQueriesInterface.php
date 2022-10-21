<?php

namespace App\Interfaces;

interface RomFileQueriesInterface
{
    function getTotalSizeOfAllFilesThatHaveRoms(): int;

    function getListOfRomFilesInStorageDirectory(): array;
}
