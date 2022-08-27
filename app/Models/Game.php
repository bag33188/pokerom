<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

}
