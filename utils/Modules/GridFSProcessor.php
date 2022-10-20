<?php

namespace Modules;

use Exception;
use MongoDB\BSON\ObjectId;
use MongoDB\GridFS\Bucket;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class GridFSProcessor
{
    /**
     * laravel storage path-string that points to storage directory where files are searched for
     *
     * ## Example
     *
     * ```php
     * 'storage/app/public/grid_files'
     * // or
     * ['storage', 'app', 'public', 'grid_files']
     * ```
     *
     * @var string|string[]
     */
    protected string|array $gridFilesStoragePath;

    protected int $contentUploadTransferSize;
    protected int $contentDownloadTransferSize;

    private readonly Bucket $gridFSBucket;
    private readonly string $gridFilesDiskPath;

    public function __construct(private readonly GridFSConnection $gridFSConnection)
    {
        $this->gridFSBucket = $this->gridFSConnection->bucket;
    }

    /**
     * @throws Exception
     * @throws DirectoryNotFoundException
     */
    public final function upload(string $filename, array $metadata = ['contentType' => "application/octet-stream"]): void
    {
        $this->parseGridStorageDirectory();

        $filepath = "{$this->gridFilesDiskPath}/${filename}";
        $stream = $this->gridFSBucket->openUploadStream($filename, [
            'metadata' => $metadata,
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
        // make sure storage path is defined
        if (empty($this->gridFilesStoragePath)) {
            throw new Exception('GridFS storage path is not set.');
        }

        // gridFilesStoragePath is defined as a string
        if (is_string($this->gridFilesStoragePath)) {
            // trim string
            $this->gridFilesStoragePath = trim($this->gridFilesStoragePath);

            // if gridFilesStoragePath starts with the storage directory, remove it from the string
            if (str_starts_with(strtolower($this->gridFilesStoragePath), 'storage'))
                $this->gridFilesStoragePath = str_replace('storage/', '', $this->gridFilesStoragePath);

            // replace all backslashes with forward slashes
            $this->gridFilesDiskPath = preg_replace('/\x{5C}/u', DIRECTORY_SEPARATOR, storage_path($this->gridFilesStoragePath));
        }

        // gridFilesStoragePath is defined as an array
        if (is_array($this->gridFilesStoragePath)) {
            // trim array values
            $this->gridFilesStoragePath = array_values(array_map(fn(string $path): string => trim($path), $this->gridFilesStoragePath));

            // if gridFilesStoragePath first array item is the storage directory, remove it from the array
            if (strtolower($this->gridFilesStoragePath[0]) === 'storage') unset($this->gridFilesStoragePath[0]);

            // join storage path array into a string
            $this->gridFilesDiskPath = storage_path(implode(DIRECTORY_SEPARATOR, $this->gridFilesStoragePath));
        }

        // check if gridFilesDiskPath is an existing directory
        if (!is_dir($this->gridFilesDiskPath)) {
            throw new DirectoryNotFoundException("GridFS storage path '{$this->gridFilesDiskPath}' does not exist.");
        }
    }
}
