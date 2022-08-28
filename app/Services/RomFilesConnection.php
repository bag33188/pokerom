<?php

namespace App\Services;

use GridFS\Client\AbstractGridFSConnection;

class RomFilesConnection extends AbstractGridFSConnection
{
    protected bool $loadConnectionInfoFromConfig = true;
    protected bool $useAuth = true;
    protected string $authMechanism = 'SCRAM-SHA-256';

    function __construct(RomFilesDatabase $romFilesDatabase)
    {
        parent::__construct($romFilesDatabase);
    }
}
