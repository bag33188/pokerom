<?php

namespace App\Services;

use GridFS\Support\GridFSProcessor;

class RomFileProcessor extends GridFSProcessor
{
    protected string $gridFilesStoragePath = 'app/public/rom_files';

    protected int $contentUploadTransferSize = 0xFF000;
    protected int $contentDownloadTransferSize = 0xFF000;

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
