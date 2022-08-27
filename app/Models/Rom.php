<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Relations\HasOne as HasOneDocument;

class Rom extends Model
{
    use HybridRelations, HasFactory;

    protected $connection = 'mysql';
    protected $table = 'roms';
    protected $fillable = ['rom_name', 'rom_size', 'rom_type'];
    protected $guarded = ['file_id', 'game_id', 'has_game', 'has_file'];
    protected $attributes = [
        'rom_size' => 1020,
        'has_file' => false,
        'has_game' => false
    ];
    protected $casts = [
        'has_file' => 'boolean',
        'has_game' => 'boolean',
    ];

    public function game(): HasOne
    {
        return $this->hasOne(Game::class, 'rom_id', 'id');
    }

    public function romFile(): HasOneDocument
    {
        return $this->hasOne(RomFile::class, '_id', 'file_id');
    }
}
