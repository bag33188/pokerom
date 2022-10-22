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
    public function __construct(private readonly RomFile $romFile)
    {
    }

    public function uploadToGrid(string $romFilename): RomFile
    {
        $this->romFile->normalizeRomFilename($romFilename);
        ProcessRomFileUpload::dispatch($romFilename);
        $romFile = $this->romFile->where('filename', $romFilename)->first();
        RomFileCreated::dispatch($romFile);
        return $romFile;
    }

    public function downloadFromGrid(RomFile $romFile): RomFile
    {
        $romFileId = $romFile->getKey();
        ProcessRomFileDownload::dispatchNow($romFileId);
        return $romFile;
    }

    public function deleteFromGrid(RomFile $romFile): RomFile
    {
        // clone romFile object for use in method's return value
        $romFileClone = $romFile->replicateQuietly(); // mute extraneous events while cloning
        RomFileDeleting::dispatch($romFile);
        ProcessRomFileDeletion::dispatchSync($romFile->getKey());
        return $romFileClone;
    }

    public function determineConsole(RomFile $romFile): string
    {
        $fileType = $romFile->getFileExtension();
        return match (strtoupper($fileType)) {
            'GB' => 'Gameboy',
            'GBC' => 'Gameboy Color',
            'GBA' => 'Gameboy Advance',
            'NDS' => 'Nintendo DS',
            '3DS' => 'Nintendo 3DS',
            'XCI' => 'Nintendo Switch',
            default => 'Unknown Console',
        };
    }

}
