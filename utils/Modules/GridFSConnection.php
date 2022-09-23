<?php

namespace Utils\Modules;

use MongoDB\Client as MongoClient;
use MongoDB\GridFS\Bucket;

class GridFSConnection
{
    public string $databaseName;
    public string $bucketName;
    public int $chunkSize;
    protected string $host;
    protected string $port;
    protected bool $useAuth;
    protected string $authDatabase;
    protected string $authMechanism = 'SCRAM-SHA-1';
    protected string $usernameConfigPath;
    protected string $passwordConfigPath;
    public readonly Bucket $bucket;

    public function __construct()
    {
        $this->setBucket();
    }

    function mongoURI()
    {
        $dsn = "mongodb://{$this->host}:{$this->port}/?authSource={$this->authDatabase}";
        return $dsn;
    }

    public function connect()
    {
//        $authObj = ($this->useAuth) ?
//            ['username' => config($this->usernameConfigPath), 'password' => $this->passwordConfigPath, 'authSource' => $this->authDatabase, 'authMechanism' => $this->authMechanism] : [];
        $db = new MongoClient($this->mongoURI(), uriOptions: [
            'username' => config($this->usernameConfigPath), 'password' => config($this->passwordConfigPath), 'authMechanism' => $this->authMechanism]
        );
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
