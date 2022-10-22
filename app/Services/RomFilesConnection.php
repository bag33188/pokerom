<?php

namespace App\Services;

use Modules\GridFSConnection;

class RomFilesConnection extends GridFSConnection
{
    protected string $host = 'localhost';
    protected int $port = 27017;

    protected bool $useAuth = true;
    protected string $authDatabase = 'admin';
    protected string $authMechanism = parent::AUTH_METHODS[256];
    protected string $usernameConfigPath = 'database.connections.mongodb.username';
    protected string $passwordConfigPath = 'database.connections.mongodb.password';

    public string $databaseName = 'pokerom_files';
    public string $bucketName = 'rom';
    public int $chunkSize = 0xFF000;
}
