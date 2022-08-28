<?php

namespace GridFS\Client;

use GridFS\GridFS;

/**
 * GridFS Database Class for defining a MongoDB Database
 */
abstract class AbstractGridFSDatabase extends GridFS
{
    /** @var string[] */
    private static array $gfsConfig;
    /** @var string[] */
    private static array $mongoConfig;


    public function __construct(string $databaseName = null, string $bucketName = null, int $chunkSize = null)
    {
        $this->setConfigVars();

        if (empty($this->databaseName)) {
            $this->set_database_name($databaseName ?? self::$gfsConfig['connection']['database']);
        }
        if (empty($this->bucketName)) {
            $this->set_bucket_name($bucketName ?? self::$gfsConfig['bucketName']);
        }
        if (empty($this->chunkSize)) {
            $this->set_chunk_size($chunkSize ?? self::$gfsConfig['chunkSize']);
        }
    }

    private function setConfigVars(): void
    {
        self::$gfsConfig = config('gridfs');
        self::$mongoConfig = config('database.connections.mongodb');
    }

}
