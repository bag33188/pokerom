<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model as MongoDbModel;
use MongoDB\BSON\ObjectId;
use DateTimeInterface;
use Utils\Classes\AbstractGridFSModel as GridFSModel;

/** @mixin GridFSModel */
class RomFile extends MongoDbModel
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'rom.files';
    protected $table = 'rom.files'; /*! <- don't delete!! use for eloquent helper code */
    protected $primaryKey = '_id';
    protected $keyType = 'string';

    protected $casts = [
        'uploadDate' => 'datetime'
    ];

    public function rom(): BelongsTo
    {
        return $this->belongsTo(Rom::class, '_id', 'file_id');
    }

    public function getObjectId(): ObjectId
    {
        return new ObjectId(strval($this->getKey()));
    }

    public function calculateRomSizeFromLength(): int
    {
        return (int)ceil($this->attributes['length'] / DATA_BYTE_FACTOR);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
