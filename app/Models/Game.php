<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = "games";
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'game_name',
        'game_type',
        'generation',
        'date_released',
        'region',
    ];
    protected $guarded = ['rom_id'];
    protected $casts = ['date_released' => 'date']; # 'date:Y-m-d'

    public function rom(): BelongsTo
    {
        return $this->belongsTo(Rom::class, 'rom_id', 'id', 'rom');
    }

    protected function gameName(): Attribute
    {
        return Attribute::make(
            get: fn($value) => preg_replace(POKE_PATTERN, POKE_EACUTE, $value),
            set: fn($value) => preg_replace("/^(pok[e\x{E9}\x{C9}]mon)/ui", 'Pokemon', $value)
        );
    }

    protected function region(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ucfirst($value),
            set: fn($value) => strtolower($value)
        );
    }

    protected function gameType(): Attribute
    {
        return Attribute::make(
            get: fn($value) => str_capitalize($value, true, '-', 2),
            set: fn($value) => strtolower($value)
        );
    }

    public static function slugifyGameName(string $gameName): string
    {
        return Str::slug($gameName);
    }
}
