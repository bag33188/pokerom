<?php

namespace App\Services;

use GridFS\Client\AbstractGridFSConnection;

class RomFilesConnection extends AbstractGridFSConnection
{
    protected bool $loadConnectionInfoFromConfig = true;

    function __construct(RomFilesDatabase $romFilesDatabase)
    {
        parent::__construct($romFilesDatabase);
    }
}
