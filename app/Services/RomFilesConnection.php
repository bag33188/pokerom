<?php

namespace App\Services;

use Utils\Modules\GridFSConnection;

class RomFilesConnection extends GridFSConnection
{
    protected string $host = 'localhost';
    protected string $port = '27017';
    protected bool $useAuth = true;
    protected string $authDatabase = 'admin';
    protected string $authMechanism = 'SCRAM-SHA-256';

    protected string $usernameConfigPath = 'database.connections.mongodb.username';
    protected string $passwordConfigPath = 'database.connections.mongodb.password';

}
