<?php

namespace App\Services;

use GridFS\Client\AbstractGridFSDatabase as GridFSDatabase;

class RomFilesDatabase extends GridFSDatabase
{
    protected string $databaseName = 'pokerom_files';
    protected string $bucketName = 'rom';
    protected int $chunkSize = 0xFF000; //! 1044480 / 1020 kibibytes

    /**
     * Create new GridFS Database Instance
     */
    public function __construct(string $databaseName, string $bucketName, int $chunkSize)
    {
        parent::__construct($databaseName, $bucketName, $chunkSize);
    }
}
