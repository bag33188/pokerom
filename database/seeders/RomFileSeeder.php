<?php

namespace Database\Seeders;

use App\Models\RomFile;
use Illuminate\Database\Seeder;

# use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RomFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        RomFile::factory()->count(10)->create();
    }
}
