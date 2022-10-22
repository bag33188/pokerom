<?php

namespace App\Models;

use Classes\AbstractGridChunkModel as GridChunkModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as MongoDbModel;
use Jenssegers\Mongodb\Relations\BelongsTo;

/** @mixin GridChunkModel */
class RomChunk extends MongoDbModel
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'rom.chunks';
    protected $table = 'rom.chunks'; /*! <- don't delete!! use for eloquent helper code */
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [
        'files_id',
        'n',
        'data',
    ];

    public function romFile(): BelongsTo
    {
        return $this->belongsTo(RomFile::class, 'files_id', '_id', 'romFile');
    }
}
