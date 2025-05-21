<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{

    // public function headingRow(): int
    // {
    //     return 1;
    // }
    public function model(array $row)
    {

        // dd($row);
        return new Siswa([
            'nis' => $row['nis'],
            'nisn' => $row['nisn'],
            'nama_siswa' => $row['nama_siswa'],
            'nama_ayah' => $row['nama_ayah'],
            'nama_ibu' => $row['nama_ibu'],
            'alamat' => $row['alamat'],
            'kelas' => $row['kelas'],
        ]);
    }
}
