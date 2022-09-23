<?php

namespace Utils\Modules;

use MongoDB\Client as MongoClient;
use MongoDB\Driver\Exception\RuntimeException as DriverRuntimeException;
use MongoDB\GridFS\Bucket;

class GridFSConnection
{
    protected string $host = 'localhost';
    protected int $port = 27017;
    protected bool $useAuth = false;

    protected string $authDatabase = 'admin';
    protected string $authMechanism = 'SCRAM-SHA-1';
    protected string $usernameConfigPath;
    protected string $passwordConfigPath;

    public string $databaseName = 'test';
    public string $bucketName = 'fs';
    public int $chunkSize = 0x3FC00;

    public readonly Bucket $bucket;

    public function __construct()
    {
        try {
            $this->connectGfs();
        } catch (DriverRuntimeException $e) {
            throw new DriverRuntimeException("Error connecting to GridFS: {$e->getMessage()}");
        }
    }

    /**
     * @link https://www.mongodb.com/docs/manual/reference/connection-string/
     * @param bool|null $useFullMongoURI
     * @return string
     */
    private function dsn(?bool $useFullMongoURI = null): string
    {
        #!empty($useFullMongoURI) && $useFullMongoURI === true
        if ($useFullMongoURI) {

            list($username, $password) = array_values(
                config()->getMany([
                    $this->usernameConfigPath,
                    $this->passwordConfigPath,
                ])
            );

            $dsn = _SPACE .
                'mongodb' . '://' .
                "${username}:${password}" . '@' .
                "{$this->host}:{$this->port}" . '/' .
                $this->databaseName . '?' .
                "authSource={$this->authDatabase}" . '&' .
                "authMechanism={$this->authMechanism}";

            return ltrim($dsn);
        } else {
            return "mongodb://{$this->host}:{$this->port}/";
        }
    }

    private function parseDbCredentials(): array
    {
        return $this->useAuth ? [
            'username' => config($this->usernameConfigPath),
            'password' => config($this->passwordConfigPath),
            'authSource' => $this->authDatabase,
            'authMechanism' => $this->authMechanism
        ] : [];
    }

    /**
     * @link https://www.php.net/manual/en/mongodb-driver-manager.construct.php
     * @return void
     */
    private function connectGfs(): void
    {
        $connection = new MongoClient(uri: $this->dsn(false), uriOptions: $this->parseDbCredentials());
        $db = $connection->selectDatabase($this->databaseName);
        $this->bucket = $db->selectGridFSBucket([
            'chunkSizeBytes' => $this->chunkSize,
            'bucketName' => $this->bucketName
        ]);
    }
}
