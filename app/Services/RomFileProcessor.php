<?php

namespace App\Services;

use Utils\Modules\GridFSProcessor;

class RomFileProcessor extends GridFSProcessor
{
    protected array|string $gridFilesStoragePath = ['storage', 'app', 'public', 'rom_files'];

    protected int $contentUploadTransferSize = 0b11111111000000000000;
    protected int $contentDownloadTransferSize = 0b11111111000000000000;

    public function __construct(RomFilesConnection $romFilesConnection)
    {
        parent::__construct($romFilesConnection);
    }
}
