<?php

namespace App\Services;

class RomFilesConnection extends \GridFS\Client\AbstractGridFSConnection
{
    protected string $entityName = 'pokerom_files.mongo';

    function __construct(RomFilesDatabase $romFilesDatabase)
    {
        parent::__construct($romFilesDatabase);
    }
}
