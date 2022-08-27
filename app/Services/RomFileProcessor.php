<?php

namespace App\Services;

class RomFileProcessor extends \GridFS\Support\GridFSProcessor
{
    protected string $entityName = 'pokerom_files.gridfs';

    protected string $gridStoragePath = 'app/public/rom_files';

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
