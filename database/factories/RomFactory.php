<?php

namespace Database\Factories;

use App\Models\Rom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rom>
 */
class RomFactory extends Factory
{
    protected $connection = 'mysql';
    protected $model = Rom::class;
    protected $has = [
        'game' => GameFactory::class,
        'romFile' => RomFileFactory::class,
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $path = str_replace('public', 'data/seeds.json', $_SERVER['DOCUMENT_ROOT']);
        list('roms' => $roms) = json_decode(file_get_contents($path), true);
        $randomRom = $roms[array_rand($roms)];
        return [
            'id' => $randomRom['id'],
            'rom_name' => $randomRom['rom_name'],
            'rom_type' => $randomRom['rom_type'],
            'rom_size' => $randomRom['rom_size'],
            'game_id' => $randomRom['game_id'],
            'file_id' => $randomRom['file_id'],
            'has_file' => $randomRom['has_file'],
            'has_game' => $randomRom['has_game'],
        ];
    }
}
