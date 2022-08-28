<?php

namespace App\Services;

use GridFS\Client\AbstractGridFSDatabase;

class RomFilesDatabase extends AbstractGridFSDatabase
{
    protected string $databaseName = 'pokerom_files';
    protected string $bucketName = 'rom';
    protected int $chunkSize = 0xFF000; //! 1044480 / 1020 kibibytes

    /**
     * Create new GridFS Database Instance
     */
    public function __construct()
    {
        parent::__construct();
    }
}
