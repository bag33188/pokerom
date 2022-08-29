<?php

namespace App\Services;

use GridFS\Client\AbstractGridFSConnection as GridFSConnection;

class RomFilesConnection extends GridFSConnection
{
    protected string $connectionName = 'PokeROM Files GridFS Connection';

    protected bool $useConfig = true;

    function __construct(RomFilesDatabase $romFilesDatabase)
    {
        parent::__construct($romFilesDatabase);
    }
}
