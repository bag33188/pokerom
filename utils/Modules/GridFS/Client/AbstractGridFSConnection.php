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
    protected array $uriOptions;
    protected bool $loadConnectionInfoFromConfig = true;
    protected bool $useAuth;

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

    public final function mongoURI(): ?string
    {
        if ($this->loadConnectionInfoFromConfig === true) {
            // does not have compatibility with atlas
            $dsnBuilder = _SPACE .
                self::$mongoConfig['driver'] . '://' .
                (self::$mongoConfig->has('username') ? (self::$mongoConfig['username'] . ':' . self::$mongoConfig['password'] . '@') : '') .
                self::$mongoConfig['host'] . ':' .
                self::$mongoConfig['port'] . '/?' . 'authMechanism=' .
                (self::$mongoConfig->has('options.authMechanism') ? self::$mongoConfig['options']['authMechanism'] : 'DEFAULT')
                . '&authSource=' . (self::$mongoConfig->has('options.authSource') ? self::$mongoConfig['options.authSource'] : 'admin');
        } else {
            return null;
        }

//        if ($this->useAuth === true) {
//            $dsnBuilder .= '?' .
//                'authMechanism=' .
//                (@self::$mongoConfig['options']['authMechanism'] ?? 'DEFAULT') .
//                '&authSource=' .
//                (@self::$mongoConfig['options']['authSource'] ?? 'admin');
//        }
        return ltrim($dsnBuilder, _SPACE);
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
        $db = new MongoClient($this->loadConnectionInfoFromConfig === true ? $this->dsn : $this->uriOptions);
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
