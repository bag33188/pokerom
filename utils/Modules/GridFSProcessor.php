<?php

namespace Utils\Modules;

use MongoDB\BSON\ObjectId;
use MongoDB\GridFS\Bucket;

class GridFSProcessor
{
    protected string|array $gridFilesStoragePath;

    protected int $contentUploadTransferSize;
    protected int $contentDownloadTransferSize;

    private readonly Bucket $gridFSBucket;
    private readonly string $gridFilesDiskPath;

    public function __construct(private readonly GridFSConnection $gridFSConnection)
    {
        $this->gridFSBucket = $this->gridFSConnection->bucket;

        $this->parseGridStorageDirectory();
    }

    private function parseGridStorageDirectory(): void
    {
        if (empty($this->gridFilesStoragePath)) {
            $this->gridFilesStoragePath = 'app/public/grid_files';
        }

        if (is_string($this->gridFilesStoragePath)) {
            if (str_starts_with(strtolower($this->gridFilesStoragePath), 'storage'))
                $this->gridFilesStoragePath = str_replace('storage/', '', $this->gridFilesStoragePath);

            $this->gridFilesDiskPath = preg_replace('/\x{5C}/u', DIRECTORY_SEPARATOR, storage_path($this->gridFilesStoragePath));
        }

        if (is_array($this->gridFilesStoragePath)) {
            if (strtolower($this->gridFilesStoragePath[0]) === 'storage')
                array_splice($this->gridFilesStoragePath, 0, 1); # unset($this->gridFilesStoragePath[0]);

            $this->gridFilesDiskPath = preg_replace('/\x{5C}/u', DIRECTORY_SEPARATOR, storage_path(implode(DIRECTORY_SEPARATOR, $this->gridFilesStoragePath)));
        }
    }

    public final function upload(string $filename): void
    {
        $filepath = "{$this->gridFilesDiskPath}/${filename}";
        $stream = $this->gridFSBucket->openUploadStream($filename, ['chunkSizeBytes' => $this->gridFSConnection->chunkSize]);
        $fileUploader = new FileUploader($stream, $filepath, $this->contentUploadTransferSize);
        $fileUploader->uploadFile();
    }

    public final function download(ObjectId $fileId): void
    {
        $stream = $this->gridFSBucket->openDownloadStream($fileId);
        $fileDownloader = new FileDownloader($stream, $this->contentDownloadTransferSize);
        $fileDownloader->downloadFile();
    }

    public final function delete(ObjectId $fileId): void
    {
        $this->gridFSBucket->delete($fileId);
    }
}
