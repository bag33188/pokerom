<?php

/*
 * Custom module for downloading large files.
 *
 * Uses proper streaming to download excessively long files with mass binary content.
 *
 * useful links:
 * https://www.php.net/readfile
 */

namespace Utils\Modules;

class FileDownloader
{
    /** @var resource */
    private $fileStream;

    /** @var int */
    private int $fileBufferSize;

    /**
     * @param resource $fileStream
     * @param int $fileBufferSize
     */
    public function __construct($fileStream, int $fileBufferSize = 0b00111111110000000000)
    {
        $this->fileStream = $fileStream;
        $this->fileBufferSize = $fileBufferSize;
    }

    /**
     * Download file on class invocation.
     *
     * @return void
     * @see downloadFile
     */
    public function __invoke(): void
    {
        $this->downloadFile();
    }

    private function isEndOfFile(): bool
    {
        return feof($this->fileStream);
    }

    private function getCurrentFileBuffer(): false|string
    {
        return fread($this->fileStream, $this->fileBufferSize);
    }

    private function closeFileStream(): void
    {
        fclose($this->fileStream);
    }

    private function printOutCurrentFileBuffer(): void
    {
        echo $this->getCurrentFileBuffer();
    }

    private function printBytesIfNotEndOfFile(): void
    {
        while (!$this->isEndOfFile()) {
            $this->printOutCurrentFileBuffer();
            self::flushBufferDownStream();
        }
    }

    public function downloadFile(): void
    {
        $this->printBytesIfNotEndOfFile();
        $this->closeFileStream();
    }

    /**
     * flush output buffer
     * @return void
     */
    private static function flushBufferDownStream(): void
    {
        flush();
    }
}
