<?php

namespace App\Queries;

use App\Interfaces\RomFileQueriesInterface;
use App\Models\RomFile;

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
}
