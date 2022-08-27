<?php

namespace App\Services;

use GridFS\Client\AbstractGridFSConnection;

class RomFilesConnection extends AbstractGridFSConnection
{
    protected string $entityName = 'pokerom_files.mongo';

    function __construct(RomFilesDatabase $romFilesDatabase)
    {
        parent::__construct($romFilesDatabase);
    }
}
