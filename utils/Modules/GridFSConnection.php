<?php

namespace Utils\Modules;

use MongoDB\Client as MongoClient;
use MongoDB\GridFS\Bucket;

class GridFSConnection
{
    protected string $host;
    protected string $port;
    protected bool $useAuth;
    protected string $authDatabase;
    protected string $authMechanism = 'SCRAM-SHA-1';
    protected string $usernameConfigPath;
    protected string $passwordConfigPath;
    public readonly Bucket $bucket;

    public function __construct(public readonly string $databaseName, public readonly string $bucketName, public readonly int $chunkSize)
    {
        //"mongodb://brock:3931Sunflower!@localhost:27017/?authSource=admin&authMechanism=SCRAM-SHA-256"
        $this->setBucket();
    }

    function mongoURI()
    {
        $dsn = "mongodb://{$this->host}:{$this->port}/";
        if ($this->useAuth) {
            $dsn .= "?authSource={$this->authDatabase}&authMechanism={$this->authMechanism}";
        }
        return $dsn;
    }

    public function connect()
    {
        $authObj = ($this->useAuth) ? ['username' => config($this->usernameConfigPath), 'password' => $this->passwordConfigPath] : [];
        $db = new MongoClient($this->mongoURI(), $authObj);
        return $db->selectDatabase($this->databaseName);
    }

    function setBucket()
    {
        $this->bucket = $this->connect()->selectGridFSBucket([
            'chunkSizeBytes' => $this->chunkSize,
            'bucketName' => $this->bucketName
        ]);
    }
}
