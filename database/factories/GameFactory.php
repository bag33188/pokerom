<?php

namespace Database\Factories;

use App\Models\Game;
use Date;
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
        list('games' => $games) = json_decode(file_get_contents($path), true);
        $randomGame = $games[array_rand($games)];
        return [
            'id' => $randomGame['id'],
            'game_name' => $randomGame['game_name'],
            'slug' => Game::slugifyGameName($randomGame['game_name']),
            'date_released' => Date::create($randomGame['date_released'])->format('Y-m-d'),
            'generation' => $randomGame['generation'],
            'game_type' => $randomGame['game_type'],
            'region' => $randomGame['region'],
        ];
    }
}
