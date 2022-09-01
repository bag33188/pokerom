<?php

/*
 * Custom module for uploading large files.
 *
 * Uses proper streaming to upload excessively long files with mass binary content.
 *
 * useful links:
 * https://www.php.net/readfile
 */

namespace Utils\Modules;

class FileUploader
{
    /** @var resource */
    private $fileHandle;

    /** @var resource */
    private $fileStream;

    /** @var int */
    private int $fileBufferSize;

    /**
     * @param resource $fileStream
     * @param string $filename
     * @param int $fileBufferSize
     */
    public function __construct($fileStream, string $filename, int $fileBufferSize = 0b00111111110000000000)
    {
        $this->fileStream = $fileStream;
        $this->fileHandle = self::openFileForBinaryReading($filename);
        $this->fileBufferSize = $fileBufferSize;
    }

    public function __invoke(): void
    {
        $this->uploadFile();
    }

    private function isEndOfFile(): bool
    {
        return feof($this->fileHandle);
    }

    private function getCurrentFileBuffer(): false|string
    {
        return fread($this->fileHandle, $this->fileBufferSize);
    }

    private function writeToStream(): void
    {
        fwrite($this->fileStream, $this->getCurrentFileBuffer());
    }

    private function appendToStreamIfNotEndOfFile(): void
    {
        while (!$this->isEndOfFile()) {
            $this->writeToStream();
            self::flushBufferUpStream();
        }
    }

    private function closeFileStream(): void
    {
        fclose($this->fileHandle);
    }

    public function uploadFile(): void
    {
        $this->appendToStreamIfNotEndOfFile();
        $this->closeFileStream();
    }

    /**
     * flush and send output buffer
     * @return void
     */
    private static function flushBufferUpStream(): void
    {
        ob_flush();
    }

    /**
     * @param string $filename
     * @return resource
     */
    private static function openFileForBinaryReading(string $filename)
    {
        return fopen($filename, 'rb'); // `rb` mode = read binary
    }
}
