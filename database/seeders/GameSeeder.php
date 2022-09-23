<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;
# use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Game::factory()->count(10)->create();
    }
}
