<?php

namespace Classes;

use Jenssegers\Mongodb\Eloquent\Model as MongoDbModel;

# use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

/**
 * Laravel IDEA Helper does not support MongoDB objects (at least not very well).
 * The purpose of this abstract class is to provider easier hinting and implementation
 * for GridFS-related models.
 */
abstract class AbstractGridFSModel extends MongoDbModel
{
    /**
     * _id
     * ---
     *
     * ### BSON Type: ObjectID (`objectId`)
     *
     * **The ID of the file.**
     *
     * @var string
     */
    public readonly string $_id;

    /**
     * chunkSize
     * ---------
     *
     * ### BSON Type: int32 (`int`)
     *
     * **Size of chunks file is stored as in grid.**
     *
     * @var int
     */
    public readonly int $chunkSize;

    /**
     * filename
     * --------
     *
     * ### BSON Data Type: String (`string`)
     *
     * **Name of file.**
     *
     * @var string
     */
    public readonly string $filename;

    /**
     * length
     * ------
     *
     * ### BSON Types: int64 (`long`), int32 (`int`)
     *
     * **Size of file in _bytes_.**
     *
     * @var int
     */
    public readonly int $length;

    /**
     * uploadDate
     * ----------
     *
     * ### BSON Type: Date (`date`)
     *
     * **Date file was uploaded.**
     *
     * @var string
     */
    public readonly string $uploadDate;

    /**
     * md5
     * ---
     *
     * ### BSON Data Type: String (`string`)
     *
     * **The `md5` hash for the file.**
     *
     * @var string
     */
    public readonly string $md5;

    public readonly array $metadata;
}
