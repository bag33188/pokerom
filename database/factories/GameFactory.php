<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Rom;
use Date;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    protected $connection = 'mysql';
    protected $model = Game::class;
    protected $for = Rom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $path = dirname(__FILE__, 3) . '\data\seeds.json';
        list('games' => $games) = json_decode(file_get_contents($path), true);
        $randomGame = $games[array_rand($games)];
        return [
            'id' => $randomGame['id'],
            'game_name' => $randomGame['game_name'],
            'slug' => Str::slug($randomGame['game_name']),
            'date_released' => Date::create($randomGame['date_released'])->format('Y-m-d'),
            'generation' => $randomGame['generation'],
            'game_type' => $randomGame['game_type'],
            'region' => $randomGame['region'],
        ];
    }
}
