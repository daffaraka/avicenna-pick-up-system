<?php

namespace Database\Seeders;

use App\Models\PenjemputanHarian;
use App\Models\User;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PenjemputanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pic = User::pluck('id')->toArray();
        $siswa = Siswa::pluck('id')->toArray();


        for ($i = 0; $i <10; $i++) {
            PenjemputanHarian::create([
                'pic_id' => $pic[array_rand($pic)],
                'siswa_id' => $siswa[$i],
                'nama_penjemput' => 'John Doe',
                'confirm_pic_at' => Carbon::now()->addMinutes(10),
                'confirm_satpam_at' =>  Carbon::now()->addMinutes(10),
                // 'waktu_dijemput' => now(),

            ]);
        }

    }
}
