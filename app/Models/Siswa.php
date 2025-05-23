<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{

    protected $fillable =
    [
        'nama_siswa',
        'kelas',
        'nis',
        'nisn',
        'alamat',
        'nama_ayah',
        'nama_ibu',
    ];


    public function penjemputan()
    {
        return $this->hasMany(PenjemputanHarian::class);
    }
}
