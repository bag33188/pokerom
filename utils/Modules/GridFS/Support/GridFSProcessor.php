<?php

namespace GridFS\Support;

use GridFS\Client\AbstractGridFSConnection;
use GridFS\GridFS;
use MongoDB\BSON\ObjectId;
use MongoDB\GridFS\Bucket;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Utils\Modules\FileDownloader;
use Utils\Modules\FileUploader;

class GridFSProcessor extends GridFS implements GridFSProcessorInterface
{

    protected string $gridFilesStoragePath;

    protected int $contentTransferSize;

    private string $symbolicGridStoragePath;

    private Bucket $gridFSBucket;

    private const BACKSLASH_PATTERN = '/\x{5C}/u';

    public function __construct(private readonly AbstractGridFSConnection $gridFSConnection)
    {
        if (empty($this->gridFilesStoragePath)) {
            $this->symbolicGridStoragePath = preg_replace(self::BACKSLASH_PATTERN, '/', config('gridfs.fileUploadPath'));
        } else {
            $this->symbolicGridStoragePath = storage_path($this->gridFilesStoragePath);
        }

        $this->setGridFSEntities();
    }

    private function setGridFSEntities(): void
    {
        $this->bucketName = $this->gridFSConnection->get_bucket_name();
        $this->chunkSize = $this->gridFSConnection->get_chunk_size();
        $this->databaseName = $this->gridFSConnection->get_database_name();

        $this->gridFSBucket = $this->gridFSConnection->bucket;
    }

    public final function upload(string $filename): void
    {
        list('filepath' => $absoluteFilePath, 'originalFileName' => $originalFileName) = $this->processFilenameMetadata($filename);
        $stream = $this->gridFSBucket->openUploadStream($originalFileName, ['chunkSizeBytes' => $this->chunkSize]);
        $fileUploader = new FileUploader($stream, $absoluteFilePath, $this->contentTransferSize);
        $fileUploader->uploadFile();
    }

    public final function download(ObjectId $fileId): void
    {
        $stream = $this->gridFSBucket->openDownloadStream($fileId);
        $fileDownloader = new FileDownloader($stream, $this->contentTransferSize);
        $fileDownloader->downloadFile();
    }

    public final function delete(ObjectId $fileId): void
    {
        $this->gridFSBucket->delete($fileId);
    }


    public static function fileIsTooBig(string $filename): bool
    {
        $seven_gibibytes = pow(2, 30) * 7;
        if (filesize($filename) >= $seven_gibibytes) {
            /*? do something for OVERLY large files?? ?*/
            return true;
        }
        return false;
    }

    private function parseUploadPath(): string|array|null
    {
        return preg_replace(self::BACKSLASH_PATTERN, "/", $this->symbolicGridStoragePath);
    }

    private function parseStoragePath(): array|string|null
    {
        $applicationBasePath = $this->getApplicationBasePath();
        return str_replace($applicationBasePath, '', $this->parseUploadPath());
    }

    private function getFileOriginalName(string $filename): string|array
    {
        $storagePath = $this->parseUploadPath();
        return str_replace("${storagePath}/", "", $filename);
    }


    private function processFilenameMetadata(string $filename): array
    {
        $this->appendUploadPathToFilename($filename);
        $originalFileName = $this->getFileOriginalName($filename);
        $this->throwExceptionIfFileDoesNotExistInAppStorage(filepath: $filename);
        return array('filepath' => $filename, 'originalFileName' => $originalFileName);
    }

    private function appendUploadPathToFilename(string &$filename): void
    {
        $storagePath = $this->parseUploadPath();
        $filename = "$storagePath/$filename";
    }

    /**
     * @param string $filepath
     * @return void
     * @throws NotFoundHttpException
     */
    private function throwExceptionIfFileDoesNotExistInAppStorage(string $filepath): void
    {
        if (!file_exists($filepath)) {
            throw new NotFoundHttpException(
                message: sprintf(
                    "Error: File `%s` does not exist on server's disk storage. Storage Path: %s",
                    $this->getFileOriginalName($filepath),
                    $this->parseStoragePath()
                )
            );
        }
    }

    private static function getApplicationBasePath(): string|array
    {
        return str_replace('/public', '', $_SERVER["DOCUMENT_ROOT"]);
    }

}
