<?php

namespace App\Services;

use Utils\Modules\GridFSConnection;

class RomFilesConnection extends GridFSConnection
{
    protected string $host = '127.0.0.1';
    protected int $port = 27017;
    protected bool $useAuth = true;
    protected string $authDatabase = 'admin';
    protected string $authMechanism = 'SCRAM-SHA-256';
    public string $databaseName = 'pokerom_files';
    public string $bucketName = 'rom';
    public int $chunkSize = 0xFF000;
    protected string $usernameConfigPath = 'database.connections.mongodb.username';
    protected string $passwordConfigPath = 'database.connections.mongodb.password';
}
