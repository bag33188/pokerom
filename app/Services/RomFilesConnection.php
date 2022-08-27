<?php

namespace App\Services;

use GridFS\Client\AbstractGridFSConnection;

class RomFilesConnection extends AbstractGridFSConnection
{
    protected string $connectionName = 'pokerom_files.mongo';

    function __construct(RomFilesDatabase $romFilesDatabase)
    {
        parent::__construct($romFilesDatabase);
    }
}
