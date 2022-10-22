<?php

namespace App\Models;

use Classes\AbstractGridFSModel as GridFSModel;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model as MongoDbModel;
use Jenssegers\Mongodb\Relations\HasMany;
use MongoDB\BSON\ObjectId;

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
        'uploadDate' => 'datetime',
    ];

    public function rom(): BelongsTo
    {
        return $this->belongsTo(Rom::class, '_id', 'file_id');
    }

    public function romChunks(): HasMany
    {
        //C:\Users\bglat\PhpstormProjects\pokerom\vendor\jenssegers\mongodb\src\Eloquent\Model.php
        //\Jenssegers\Mongodb\Eloquent\Model::getIdAttribute
        //https://github.com/jenssegers/laravel-mongodb/issues/1902#issuecomment-882694504
        return $this->hasMany(RomChunk::class, 'files_id', '_id');
    }

    public function getKeyAsObjectId(): ObjectId
    {
        return new ObjectId(strval($this->getKey()));
    }

    public function calculateRomSizeFromLength(): int
    {
        $baseUnitValue = 0x400; // 1024
        return (int)ceil($this->attributes['length'] / $baseUnitValue);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getFileType(bool $includeFullStop = true): string
    {
        $romFileType = explode(_FULLSTOP, $this->attributes['filename'], 2)[1];
        return $includeFullStop === false ? $romFileType : _FULLSTOP . $romFileType;
    }

    public function getChunkSizeInBits(): int
    {
        return $this->attributes['chunkSize'] * 8;
    }

    public static function normalizeRomFilename(string &$romFilename): void
    {
        list($name, $ext) = explode('.', $romFilename, 2);
        $name = trim($name);
        $ext = strtolower($ext);
        $romFilename = "${name}.${ext}";
    }
}
