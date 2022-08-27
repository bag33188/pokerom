<?php

namespace App\Services;

class RomFilesDatabase extends \GridFS\Client\AbstractGridFSDatabase
{
    protected string $entityName = 'pokerom_files.db';

    protected bool $useAuth = true;

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
