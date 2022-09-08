<?php

namespace GridFS\Support;

use MongoDB\BSON\ObjectId;

interface GridFSProcessorFactory
{
    function upload(string $filename): void;

    function download(ObjectId $fileId): void;

    function delete(ObjectId $fileId): void;

    static function fileIsTooBig(string $filename): bool;
}
