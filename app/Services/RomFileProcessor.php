<?php

namespace App\Services;

use GridFS\Support\GridFSProcessor;

class RomFileProcessor extends GridFSProcessor
{
    protected string $entityName = 'pokerom_files.gridfs';

    protected string $gridStoragePath = 'app/public/rom_files';

    protected int $contentTransferSize = 0xFF000;

    /**
     * Create new GridFS Processor Instance
     *
     * @param RomFilesConnection $romFilesConnection
     */
    public function __construct(RomFilesConnection $romFilesConnection)
    {
        parent::__construct($romFilesConnection);
    }
}
