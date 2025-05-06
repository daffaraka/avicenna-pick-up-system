<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $kelas = [
            '1A',
            '1B',
            '1C',
            '2A',
            '2B',
            '2C',
            '3A',
            '3B',
            '3C',
        ];

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        User::factory()->create([
            'name' => 'Satpam 1',
            'email' => 'satpam1@satpam.com',
            'password' => bcrypt('password'),
            'role' => 'satpam',
        ]);
        User::factory()->create([
            'name' => 'Satpam 2',
            'email' => 'satpam2@satpam.com',
            'password' => bcrypt('password'),
            'role' => 'satpam',
        ]);
        User::factory()->create([
            'name' => 'Satpam 3',
            'email' => 'satpam3@satpam.com',
            'password' => bcrypt('password'),
            'role' => 'satpam',
        ]);
        shuffle($kelas);

        for ($i = 0; $i < 9; $i++) {
            $randomKelas = $kelas[$i];

            User::factory()->create([
                'name' => "Guru $randomKelas",
                'email' => "guru_$randomKelas@guru.com",
                'password' => bcrypt('password'),
                'role' => 'guru',
                'pic_kelas' => $randomKelas,
            ]);
        }
    }
}
