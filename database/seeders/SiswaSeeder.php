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
                'nama_siswa' => fake()->name(),
                'kelas' => fake()->randomElement(['X', 'XI', 'XII']),
                'foto' => fake()->imageUrl()
            ]);
        }
    }
}
