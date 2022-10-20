<?php

namespace Classes;

use Jenssegers\Mongodb\Eloquent\Model as MongoDbModel;
use MongoDB\BSON\Binary;
use MongoDB\BSON\ObjectId;

abstract class AbstractGridChunkModel extends MongoDbModel
{
    /**
     * Primary key/id of the chunk.
     * @var string|ObjectId $_id
     */
    public readonly string|ObjectId $_id;
    /**
     * Binary data representation of the chunk.
     * @var Binary $data
     */
    public readonly Binary $data;
    /**
     * Foreign key/id to the file object.
     * @var string|ObjectId $files_id
     */
    public readonly string|ObjectId $files_id;
    /**
     * The chunk serial/indexed number of the file.
     * @var int $n
     */
    public readonly int $n;
}
