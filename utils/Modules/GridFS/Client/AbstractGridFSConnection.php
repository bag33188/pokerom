<?php

namespace GridFS\Client;

use GridFS\GridFS;
use MongoDB\Client as MongoClient;
use MongoDB\Database;
use MongoDB\GridFS\Bucket;

/**
 * GridFS Connection Class for connection to a GridFS MongoDB Database
 *
 * _Constructor requires a {@see AbstractGridFSDatabase GridFSDatabase} Object_
 */
abstract class AbstractGridFSConnection extends GridFS
{
    /**
     * Use options defined in database.php config file
     * @var bool
     */
    protected bool $useConfig = true;

    /**
     * MongoDB URI Options
     * @link https://www.php.net/manual/en/mongodb-driver-manager.construct.php
     * @var array
     */
    protected array $uriOptions;

    /**
     * MongoDB Connection String
     * @link https://www.mongodb.com/docs/manual/reference/connection-string/
     * @var string $dsn
     */
    private readonly string $dsn;

    /** @var Bucket $bucket gridfs bucket object */
    public readonly Bucket $bucket;

    private static array $mongoConfig;

    public function __construct(private readonly AbstractGridFSDatabase $gridFSDatabase)
    {
        self::$mongoConfig = config('database.connections.mongodb');

        $this->setConnectionValues();
        $this->selectFileBucket();
    }

    public final function mongoURI(): string
    {
        if ($this->useConfig === true) {
            $detectedConfigUsesAuth = config()->has('database.connections.mongodb.username') || config()->has('database.connections.mongodb.password');
            if ($detectedConfigUsesAuth) {
                $dsnBuilder = _SPACE .
                    self::$mongoConfig['driver'] . '://' .
                    self::$mongoConfig['username'] . ':' .
                    self::$mongoConfig['password'] . '@' .
                    self::$mongoConfig['host'] . ':' .
                    self::$mongoConfig['port'] . '/?' .
                    'authMechanism=' . (@self::$mongoConfig['options']['authMechanism'] ?? 'DEFAULT') .
                    '&authSource=' . (@self::$mongoConfig['options']['authSource'] ?? 'admin');
            } else {
                $dsnBuilder = _SPACE .
                    self::$mongoConfig['driver'] . '://' .
                    self::$mongoConfig['host'] . ':' .
                    self::$mongoConfig['port'] . '/';
            }
            return ltrim($dsnBuilder, _SPACE);
        } else {
            return '';
        }
    }

    protected function setConnectionValues(): void
    {
        $this->databaseName = $this->gridFSDatabase->get_database_name();
        $this->bucketName = $this->gridFSDatabase->get_bucket_name();
        $this->chunkSize = $this->gridFSDatabase->get_chunk_size();
        $this->dsn = $this->mongoURI();
    }

    private function connectToMongoClient(): Database
    {
        $db = $this->useConfig === true ? new MongoClient($this->dsn) : new MongoClient($this->uriOptions);
        return $db->selectDatabase($this->databaseName);
    }

    private function selectFileBucket(): void
    {
        $mongodb = $this->connectToMongoClient();
        $this->bucket = $mongodb->selectGridFSBucket([
            'chunkSizeBytes' => $this->chunkSize,
            'bucketName' => $this->bucketName
        ]);
    }
}
