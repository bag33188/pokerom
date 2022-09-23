<?php

namespace Database\Factories;

use App\Models\Rom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rom>
 */
class RomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $path = str_replace('public', 'data/seeds.json', $_SERVER['DOCUMENT_ROOT']);
        $roms = json_decode(file_get_contents($path), true)['roms'];
        $randomRom = $roms[array_rand($roms)];
        return [
            ...$randomRom
        ];
    }
}
