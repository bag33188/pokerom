<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = ['rom_name', 'rom_size', 'rom_type'];
    protected $guarded = ['file_id', 'game_id', 'has_game', 'has_file'];
    protected $attributes = [
        'rom_size' => 1020,
        'has_file' => false,
        'has_game' => false
    ];
    protected $casts = [
        'has_game' => 'bool',
        'has_file' => 'boolean',
    ];

    public function game(): HasOne
    {
        return $this->hasOne(Game::class, 'rom_id', 'id');
    }

    public function romFile(): HasOneDocument
    {
        return $this->hasOne(RomFile::class, '_id', 'file_id');
    }

    protected function romType(): Attribute
    {
        return Attribute::make(
            get: fn($value) => strtoupper($value), set: fn($value) => strtolower($value)
        );
    }

    public function getRomFileName(): string
    {
        $romName = $this->attributes['rom_name'];
        $romType = strtolower($this->attributes['rom_type']);
        return "${romName}.${romType}";
    }
}
