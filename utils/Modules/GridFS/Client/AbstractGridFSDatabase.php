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
    /**
     * specify whether to use authentication when connecting to mongodb.
     *
     * _note: only allows username/password auth_
     *
     * @var bool
     */
    protected bool $useAuth = false;

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

    public final function mongoURI(): string
    {
        // does not have compatibility with atlas
        $dsnBuilder = _SPACE .
            self::$mongoConfig['driver'] . '://' .
            self::$mongoConfig['username'] . ':' .
            self::$mongoConfig['password'] . '@' .
            self::$mongoConfig['host'] . ':' .
            self::$mongoConfig['port'] . '/';
        if ($this->useAuth === true) {
            $dsnBuilder .= '?' .
                'authMechanism=' .
                (@self::$mongoConfig['options']['authMechanism'] ?? 'DEFAULT') .
                '&authSource=' .
                (@self::$mongoConfig['options']['authSource'] ?? 'admin');
        }
        return ltrim($dsnBuilder, _SPACE);
    }
}
