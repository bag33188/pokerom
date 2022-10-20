<?php

namespace App\Services;

use Modules\GridFSConnection;

class RomFilesConnection extends GridFSConnection
{
    protected string $host = 'localhost';
    protected int $port = 27017;

    protected bool $useAuth = true;
    protected string $authDatabase = 'admin';
    protected string $authMechanism = 'SCRAM-SHA-256'; # MONGO_AUTH_METHODS[256];
    protected string $usernameConfigPath = 'database.connections.mongodb.username';
    protected string $passwordConfigPath = 'database.connections.mongodb.password';
//    protected string $username = 'brock';
//    protected string $password = '3931Sunflower!';

    public string $databaseName = 'pokerom_files';
    public string $bucketName = 'rom';
    public int $chunkSize = 0xFF000;
}
