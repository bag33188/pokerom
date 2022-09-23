<?php

namespace Utils\Modules;

use Exception;
use MongoDB\Client as MongoClient;
use MongoDB\GridFS\Bucket;

class GridFSConnection
{
    public string $databaseName;
    public string $bucketName = 'fs';
    public int $chunkSize = 0x3FC00;
    protected string $host = '127.0.0.1';
    protected string $port = '27017';
    protected bool $useAuth = false;
    protected string $authDatabase = 'admin';
    protected string $authMechanism = 'SCRAM-SHA-1';
    protected string $usernameConfigPath;
    protected string $passwordConfigPath;
    public readonly Bucket $bucket;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->connect();
    }

    private function dsn(): string
    {
        return "mongodb://{$this->host}:{$this->port}/";
    }

    private function parseDbCredentials(): array
    {
        return [
            'username' => config($this->usernameConfigPath),
            'password' => config($this->passwordConfigPath),
            'authSource' => $this->authDatabase,
            'authMechanism' => $this->authMechanism
        ];
    }

    /**
     * @throws Exception
     */
    private function connect(): void
    {
        try {
            $db = new MongoClient(uri: $this->dsn(), uriOptions: ($this->useAuth) ? $this->parseDbCredentials() : []);

            $this->bucket = $db->selectDatabase($this->databaseName)->selectGridFSBucket([
                'chunkSizeBytes' => $this->chunkSize,
                'bucketName' => $this->bucketName
            ]);
        } catch (Exception $e) {
            throw new Exception("Error connecting to GridFS: {$e->getMessage()}");
        }
    }
}
