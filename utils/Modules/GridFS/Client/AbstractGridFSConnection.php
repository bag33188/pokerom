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
     * MongoDB Connection String
     * @link https://www.mongodb.com/docs/manual/reference/connection-string/
     * @var string $dsn
     */
    private readonly string $dsn;

    /** @var Bucket $bucket gridfs bucket object */
    public readonly Bucket $bucket;

    public function __construct(private readonly AbstractGridFSDatabase $gridFSDatabase)
    {
        $this->setConnectionValues();
        $this->selectFileBucket();
    }

    protected function setConnectionValues(): void
    {
        $this->databaseName = $this->gridFSDatabase->get_database_name();
        $this->bucketName = $this->gridFSDatabase->get_bucket_name();
        $this->chunkSize = $this->gridFSDatabase->get_chunk_size();
        $this->dsn = $this->gridFSDatabase->mongoURI();
    }

    private function connectToMongoClient(): Database
    {
        $db = new MongoClient($this->dsn);
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
