<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model as MongoDbModel;
use MongoDB\BSON\ObjectId;
use Classes\AbstractGridFSModel as GridFSModel;

/** @mixin GridFSModel */
class RomFile extends MongoDbModel
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'rom.files';
    protected $table = 'rom.files'; /*! <- don't delete!! use for eloquent helper code */
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

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
        return (int)ceil($this->attributes['length'] / 0x400 /* 1024 */);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getFileType(bool $includeFullStop = true): string
    {
        $romFileType = explode('.', $this->attributes['filename'], 2)[1];
        return $includeFullStop === false ? $romFileType : "\u{2E}$romFileType";
    }

    public function getChunkSizeInBits(): int
    {
        return $this->attributes['chunkSize'] * 0x08;
    }

    public static function normalizeRomFilename(string &$romFilename): void
    {
        list($name, $ext) = explode('.', $romFilename, 2);
        $name = trim($name);
        $ext = strtolower($ext);
        $romFilename = "${name}.${ext}";
    }
}
