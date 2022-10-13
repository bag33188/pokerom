<?php

namespace Classes;

use Jenssegers\Mongodb\Eloquent\Model as MongoDbModel;
use MongoDB\BSON\Binary;
use MongoDB\BSON\ObjectId;

abstract class AbstractGridChunkModel extends MongoDbModel
{
    public readonly string|ObjectId $_id;
    public readonly Binary $data;
    public readonly string|ObjectId $files_id;
    public readonly int $n;
}
