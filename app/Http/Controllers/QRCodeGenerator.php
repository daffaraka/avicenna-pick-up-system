<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Events\TestNotification;
use App\Models\PenjemputanHarian;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeGenerator extends Controller
{
    // public function generate(Request $request)
    // {
    //     $qrCodes = [];
    //     $qrCodes['simple'] =
    //         QrCode::size(150)->generate('https://daffaraka.github.io/.io/');
    //     $qrCodes['changeColor'] =
    //         QrCode::size(150)->color(255, 0, 0)->generate('https://daffaraka.github.io/.io/');
    //     $qrCodes['changeBgColor'] =
    //         QrCode::size(150)->backgroundColor(255, 0, 0)->generate('https://daffaraka.github.io/.io/');
    //     $qrCodes['styleDot'] =
    //         QrCode::size(150)->style('dot')->generate('https://daffaraka.github.io/.io/');
    //     $qrCodes['styleSquare'] = QrCode::size(150)->style('square')->generate('https://daffaraka.github.io/.io/');
    //     $qrCodes['styleRound'] = QrCode::size(150)->style('round')->generate('https://daffaraka.github.io/.io/');



    //     dd($qrCodes);
    //     return view('dashboard.qrcode', $qrCodes);
    // }



    public function qrCodeScan()
    {
        return view('dashboard.qr-scanner');
    }


    public function scanQrCode(Request $request)
    {


        $penjemputan = PenjemputanHarian::whereHas('siswa', function ($query) use ($request) {
            $query->where('nis', $request->data);
        })
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->first();


        if ($penjemputan) {
            $penjemputan->waktu_dijemput = now();
            $penjemputan->save();
        }

        event(new TestNotification([
            'nama_siswa' =>'Penjemput atas nama '. $penjemputan->siswa->nama_siswa. ' Sudah Datang',
        ]));




        //akhir proses
        return response()->json([
            'success' => true,
            'data' => $penjemputan->siswa->nama_siswa,
            'time' => Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y')
        ]);
    }
}
