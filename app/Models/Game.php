<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Game extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = "games";
    protected $fillable = [
        'game_name',
        'game_type',
        'generation',
        'date_released',
        'region',
    ];
    protected $casts = ['date_released' => 'date:Y-m-d'];

    public function rom(): BelongsTo
    {
        return $this->belongsTo(Rom::class, 'rom_id', 'id');
    }


    protected function gameName(): Attribute
    {
        return Attribute::make(
            get: fn($value) => preg_replace("/^(Poke)/i", POKE_EACUTE, $value)
        );
    }

    protected function region(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ucfirst($value)
        );
    }

    protected function gameType(): Attribute
    {
        return Attribute::make(
            get: fn($value) => str_capitalize($value, true, '-', 2)
        );
    }

    public function setRegionAttribute(string $value): void
    {
        $this->attributes['region'] = strtolower($value);
    }

    public function setGameNameAttribute(string $value): void
    {
        $this->attributes['game_name'] = preg_replace("/^(pokemon)/i", 'Pokemon', $value);
    }

    public function setGameTypeAttribute(string $value): void
    {
        $this->attributes['game_type'] = strtolower($value);
    }
}
