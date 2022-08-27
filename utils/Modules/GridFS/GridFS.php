<?php

namespace GridFS;

abstract class GridFS
{
    /**
     * name of mongodb database
     *
     * @var string
     */
    protected string $databaseName;
    /**
     * name of gridfs file bucket (default is `fs`)
     *
     * @var string
     */
    protected string $bucketName;
    /**
     * size of chunks to store files as (default is `261120`, `255kb`)
     *
     * @var int
     */
    protected int $chunkSize;
    /**
     * unique name/identifier for each instance of module
     * @var string
     */
    protected string $entityName;

    /**
     * @return string
     */
    public final function get_entity_name(): string
    {
        return $this->entityName;
    }

    /**
     * @param string $entityName
     */
    public final function set_entity_name(string $entityName): void
    {
        $this->entityName = $entityName;
    }

    /**
     * @return string
     */
    public final function get_database_name(): string
    {
        return $this->databaseName;
    }

    /**
     * @param string $databaseName
     */
    public final function set_database_name(string $databaseName): void
    {
        $this->databaseName = $databaseName;
    }

    /**
     * @return string
     */
    public final function get_bucket_name(): string
    {
        return $this->bucketName;
    }

    /**
     * @param string $bucketName
     */
    public final function set_bucket_name(string $bucketName): void
    {
        $this->bucketName = $bucketName;
    }

    /**
     * @return int
     */
    public final function get_chunk_size(): int
    {
        return $this->chunkSize;
    }

    /**
     * @param int $chunkSize
     */
    public final function set_chunk_size(int $chunkSize): void
    {
        $this->chunkSize = $chunkSize;
    }
}
