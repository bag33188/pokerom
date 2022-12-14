<?php

namespace Database\Factories;

use App\Models\Rom;
use App\Models\RomFile;
use Illuminate\Database\Eloquent\Factories\Factory;
use MongoDB\BSON\ObjectId;

/**
 * @extends Factory<RomFile>
 */
class RomFileFactory extends Factory
{
    protected $connection = 'mongodb';
    protected $model = RomFile::class;
    protected $for = Rom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $path = dirname(__FILE__, 3) . '\data\seeds.json';
        list('rom_files' => $romFiles) = json_decode(file_get_contents($path), true);
        $randomRomFile = $romFiles[array_rand($romFiles)];
        return [
            'id' => new ObjectId($randomRomFile['_id']),
            'filename' => $randomRomFile['filename'],
            'length' => $randomRomFile['length'],
            'chunkSize' => $randomRomFile['chunkSize'],
            'md5' => md5($randomRomFile['filename']),
            'uploadDate' => now(),
            'metadata' => $randomRomFile['metadata'],
        ];
    }
}
