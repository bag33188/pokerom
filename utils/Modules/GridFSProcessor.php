<?php

namespace Utils\Modules;

use MongoDB\BSON\ObjectId;
use MongoDB\GridFS\Bucket;

class GridFSProcessor
{
    protected string $gridFilesPath;

    protected int $contentUploadTransferSize;
    protected int $contentDownloadTransferSize;

    private readonly Bucket $gridFSBucket;
    private readonly string $gridStoragePath;

    public function __construct(private readonly GridFSConnection $gridFSConnection)
    {
        $this->gridFSBucket = $this->gridFSConnection->bucket;

        if (empty($this->gridFilesPath)) {
            $this->gridFilesPath = 'app/public/grid_files';
        }

        $this->gridStoragePath = preg_replace('/\x{5C}/u', '/', storage_path($this->gridFilesPath));
    }

    public final function upload(string $filename): void
    {
        $filepath = "{$this->gridStoragePath}/${filename}";
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
