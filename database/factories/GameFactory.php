<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $path = str_replace('public', 'data/seeds.json', $_SERVER['DOCUMENT_ROOT']);
        $games = json_decode(file_get_contents($path), true)['games'];
        $randomGame = $games[array_rand($games)];
        return [
            ...$randomGame
        ];
    }
}
