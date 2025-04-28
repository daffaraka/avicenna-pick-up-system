<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            Siswa::create([
                'nis' => fake()->unique()->numberBetween(1000000000, 9999999999),
                'nama_siswa' => fake()->unique()->name(),
                'kelas' => fake()->randomElement(['1A', '1B', '1C', '2A', '2B', '2C', '3A', '3B', '3C']),
                'foto' => fake()->imageUrl()
            ]);
        }
    }
}
