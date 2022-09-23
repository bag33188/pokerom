<?php

namespace App\Services;

use Utils\Modules\GridFSProcessor;

class RomFileProcessor extends GridFSProcessor
{
    protected array|string $gridFilesStoragePath = ['storage', 'app', 'public', 'rom_files'];

    protected int $contentUploadTransferSize = 0o3770000;
    protected int $contentDownloadTransferSize = 0o3770000;

    public function __construct(RomFilesConnection $romFilesConnection)
    {
        parent::__construct($romFilesConnection);
    }
}
