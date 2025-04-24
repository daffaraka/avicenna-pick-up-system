<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{

    protected $fillable =
    [
        'nis',
        'nama_siswa',
        'kelas',
        'foto'
    ];


    public function penjemputan()
    {
        return $this->hasMany(PenjemputanHarian::class);
    }



}
