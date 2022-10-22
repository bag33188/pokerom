<?php

namespace Modules;

use MongoDB\Client as MongoClient;
use MongoDB\Driver\Exception\RuntimeException as DriverRuntimeException;
use MongoDB\GridFS\Bucket;

class GridFSConnection
{
    protected final const AUTH_METHODS = [
        0 => 'DEFAULT',
        1 => 'SCRAM-SHA-1',
        256 => 'SCRAM-SHA-256'
    ];

    // CONNECTION PROPERTIES //
    protected string $host = '127.0.0.1';
    protected int $port = 27017;
    protected string $databaseName = 'test';
    protected bool $useAuth = false;

    // AUTH PROPERTIES //
    protected string $authDatabase = 'admin';
    protected string $authMechanism = self::AUTH_METHODS[0];
    /**
     * laravel {@see \Illuminate\Support\Facades\Config config} path-string that points to username value
     *
     * ## Example
     *
     * `'database.connections.mongodb.username'`
     *
     * @var string
     */
    protected string $usernameConfigPath;
    /**
     * laravel {@see \Illuminate\Support\Facades\Config config} path-string that points to password value
     *
     * ## Example
     *
     * `'database.connections.mongodb.password'`
     *
     * @var string
     */
    protected string $passwordConfigPath;
    /** @var string allow for raw username if config path is n/a */
    protected string $username;
    /** @var string allow for raw password if config path is n/a */
    protected string $password;

    // GRIDFS PROPERTIES //
    protected string $bucketName = 'fs';
    protected int $chunkSize = 0x3FC00;
    public readonly Bucket $bucket;

    public function __construct()
    {
        try {
            $this->connectGFS();
        } catch (DriverRuntimeException $e) {
            throw new DriverRuntimeException("Error connecting to GridFS: {$e->getMessage()}");
        }
    }

    /**
     * Data Source Name
     * ----------------
     * ### Full DSN Format
     * `mongodb://{$username}:{$password}@{$host}:{$port}/{$database}?authSource={$authDb}&authMechanism={$authMethod}`
     * <br/><br/>
     * ### Minimal Format
     * `mongodb://{$host}:{$port}/{$database}`
     * <br/><br/>
     * ### About
     * This method implements the minimal format, allowing for the other parameters
     * to be passed in within the `uriOptions` array. This gives the user more control
     * over what settings are used to connect to the database.
     *
     * @link https://www.mongodb.com/docs/manual/reference/connection-string/
     * @return string
     */
    private function dsn(): string
    {
        // full dsn format:
        # mongodb://{username}:{password}@{host}:{port}/{database}?authSource={authDb}&authMechanism={authMethod}
        return "mongodb://{$this->host}:{$this->port}/";
    }

    private function parseDbCredentials(): array
    {
        return $this->useAuth ? [
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'authSource' => $this->authDatabase,
            'authMechanism' => $this->authMechanism
        ] : [];
    }

    /**
     * @link https://www.php.net/manual/en/mongodb-driver-manager.construct.php
     * @return void
     */
    private function connectGFS(): void
    {
        $connection = new MongoClient(uri: $this->dsn(), uriOptions: $this->parseDbCredentials());
        $db = $connection->selectDatabase($this->databaseName);
        $this->bucket = $db->selectGridFSBucket([
            'chunkSizeBytes' => $this->chunkSize,
            'bucketName' => $this->bucketName
        ]);
    }

    private function getUsername(): string
    {
        return empty($this->usernameConfigPath) ? $this->username : config($this->usernameConfigPath);
    }

    private function getPassword(): string
    {
        return empty($this->passwordConfigPath) ? $this->password : config($this->passwordConfigPath);
    }
}
