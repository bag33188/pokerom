<?php

namespace Modules;

use Exception;
use MongoDB\BSON\ObjectId;
use MongoDB\GridFS\Bucket;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class GridFSProcessor
{
    /** @var string|string[] */
    protected string|array $gridFilesStoragePath;

    protected int $contentUploadTransferSize;
    protected int $contentDownloadTransferSize;

    private readonly Bucket $gridFSBucket;
    private readonly string $gridFilesDiskPath;

    protected final const CONTENT_TYPES = [
        'application/octet-stream',
        'application/x-rom-file'
    ];


    public function __construct(private readonly GridFSConnection $gridFSConnection)
    {
        $this->gridFSBucket = $this->gridFSConnection->bucket;
    }

    /**
     * @throws Exception
     * @throws DirectoryNotFoundException
     */
    public final function upload(string $filename): void
    {
        $this->parseGridStorageDirectory();

        $filepath = "{$this->gridFilesDiskPath}/${filename}";
        $stream = $this->gridFSBucket->openUploadStream($filename, [
            'metadata' => [
                'contentType' => self::CONTENT_TYPES[0],
                'romType' => strtoupper(explode('.', $filename, 2)[1])
            ]
        ]);
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

    /**
     * @throws Exception
     * @throws DirectoryNotFoundException
     */
    private function parseGridStorageDirectory(): void
    {
        if (empty($this->gridFilesStoragePath)) {
            throw new Exception('GridFS storage path is not set.');
        }

        if (is_string($this->gridFilesStoragePath)) {
            $this->gridFilesStoragePath = trim(strtolower($this->gridFilesStoragePath));

            if (str_starts_with(strtolower($this->gridFilesStoragePath), 'storage'))
                $this->gridFilesStoragePath = str_replace('storage/', '', $this->gridFilesStoragePath);

            $this->gridFilesDiskPath = preg_replace('/\x{5C}/u', DIRECTORY_SEPARATOR, storage_path($this->gridFilesStoragePath));
        }

        if (is_array($this->gridFilesStoragePath)) {
            if (strtolower($this->gridFilesStoragePath[0]) === 'storage') unset($this->gridFilesStoragePath[0]);

            $this->gridFilesStoragePath = array_values(array_map(fn($path) => trim(strtolower($path)), $this->gridFilesStoragePath));
            $this->gridFilesDiskPath = storage_path(implode(DIRECTORY_SEPARATOR, $this->gridFilesStoragePath));
        }

        if (!is_dir($this->gridFilesDiskPath)) {
            throw new DirectoryNotFoundException("GridFS storage path '{$this->gridFilesDiskPath}' does not exist.");
        }
    }
}
