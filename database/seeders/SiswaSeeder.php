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
        for ($i = 1; $i <= 10; $i++) {
            Siswa::create([
                'nis' => fake()->unique()->numberBetween(1000000000, 9999999999),
                'nama_siswa' => fake()->unique()->name(),
                'kelas' => fake()->randomElement(['X', 'XI', 'XII']),
                'foto' => fake()->imageUrl()
            ]);
        }
    }
}
