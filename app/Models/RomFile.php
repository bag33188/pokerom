<?php

namespace App\Models;

use Classes\AbstractGridFilesModel as GridFilesModel;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\HasMany;

/** @mixin GridFilesModel */
class RomFile extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'rom.files';
    protected $table = 'rom.files'; /*! <- don't delete!! use for eloquent helper code */
    protected $primaryKey = '_id';
    protected $keyType = 'object';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'uploadDate' => 'datetime',
    ];

    protected $guarded = [
        'chunkSize',
        'length',
        'md5',
        'uploadDate',
    ];

    protected $fillable = [
        'filename',
        'metadata->romType',
    ];

    public function rom(): BelongsTo
    {
        return $this->belongsTo(Rom::class, '_id', 'file_id', 'rom');
    }

    public function romChunks(): HasMany
    {
        // C:\Users\bglat\PhpstormProjects\pokerom\vendor\jenssegers\mongodb\src\Eloquent\Model.php
        # \Jenssegers\Mongodb\Eloquent\Model::getIdAttribute
        // https://github.com/jenssegers/laravel-mongodb/issues/1902#issuecomment-882694504

        return $this->hasMany(RomChunk::class, 'files_id', '_id');
    }


    public function calculateRomSizeFromLength(): int
    {
        return (int)ceil($this->attributes['length'] / 1024);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getFileExtension(): string
    {
        return explode(_FULLSTOP, $this->attributes['filename'], 2)[1];
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
