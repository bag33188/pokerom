<?php

namespace Utils\Modules;

use MongoDB\BSON\ObjectId;
use MongoDB\GridFS\Bucket;

class GridFSProcessor
{
    protected string $gridFilesStoragePath;

    protected int $contentUploadTransferSize;
    protected int $contentDownloadTransferSize;

    private Bucket $gridFSBucket;
    private string $diskStoragePath;

    public function __construct(private readonly GridFSConnection $gridFSConnection)
    {
        $this->gridFSBucket = $this->gridFSConnection->bucket;
        $this->diskStoragePath = storage_path($this->gridFilesStoragePath ?? 'app/public/grid_files');
    }

    public final function upload(string $filename): void
    {
        $originalFileName = trim($filename);
        $stream = $this->gridFSBucket->openUploadStream($originalFileName, ['chunkSizeBytes' => $this->gridFSConnection->chunkSize]);
        $fileUploader = new FileUploader($stream, "{$this->diskStoragePath}/${filename}", $this->contentUploadTransferSize);
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
