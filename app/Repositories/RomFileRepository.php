<?php

namespace App\Repositories;

use App\Events\RomFileCreated;
use App\Events\RomFileDeleting;
use App\Interfaces\RomFileRepositoryInterface;
use App\Jobs\ProcessRomFileDeletion;
use App\Jobs\ProcessRomFileDownload;
use App\Jobs\ProcessRomFileUpload;
use App\Models\RomFile;

class RomFileRepository implements RomFileRepositoryInterface
{

    public function uploadToGrid(string $romFilename): RomFile
    {
        RomFile::normalizeRomFilename($romFilename);
        ProcessRomFileUpload::dispatchSync($romFilename);
        $romFile = RomFile::where('filename', $romFilename)->first();
        RomFileCreated::dispatch($romFile);
        return $romFile;
    }

    public function downloadFromGrid(RomFile $romFile): RomFile
    {
        $romFileId = $romFile->getObjectId();
        ProcessRomFileDownload::dispatchSync($romFileId);
        return $romFile;
    }

    public function deleteFromGrid(RomFile $romFile): RomFile
    {
        // clone romFile object for use in method's return value
        $romFileClone = $romFile->replicateQuietly(); // mute extraneous events while cloning
        RomFileDeleting::dispatch($romFile);
        ProcessRomFileDeletion::dispatchSync($romFile->getObjectId());
        return $romFileClone;
    }
}
