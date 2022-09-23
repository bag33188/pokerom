<?php

namespace Utils\Modules;

use MongoDB\BSON\ObjectId;
use MongoDB\GridFS\Bucket;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GridFSProcessor
{
    protected string $gridFilesStoragePath;

    protected int $contentUploadTransferSize;
    protected int $contentDownloadTransferSize;


    private Bucket $gridFSBucket;


    public function __construct(private readonly GridFSConnection $gridFSConnection)
    {
        $this->gridFSBucket = $this->gridFSConnection->bucket;

    }

    public final function upload(string $filename): void
    {
        $originalFileName = trim($filename);
        $stream = $this->gridFSBucket->openUploadStream($originalFileName, ['chunkSizeBytes' => $this->gridFSConnection->chunkSize]);
        $fileUploader = new FileUploader($stream, $filename, $this->contentUploadTransferSize);
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
