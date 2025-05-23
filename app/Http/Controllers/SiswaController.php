<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SiswaController extends Controller
{

    public function index()
    {
        if (Auth::user()->role != 'admin') {
            $siswas = Siswa::whereKelas(Auth::user()->pic_kelas)->get();
        } else {
            $siswas = Siswa::all();
        }
        return view('dashboard.siswa.siswa-index', compact('siswas'));
    }



    public function generateQrCode(Siswa $siswa)
    {

        $name = $siswa->nis;


        return response()->streamDownload(function () use ($name) {
            echo file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . $name);
        }, $siswa->nis . '-' . $siswa->nama_siswa . '-' . $siswa->kelas . '.png');
        // $qrCodes = [];
        // $svg =
        // QrCode::format('svg')->size(200)->generate('https://contoh.com');
        // $qrCodes['changeColor'] =
        //     QrCode::size(150)->color(255, 0, 0)->generate('https://daffaraka.github.io/.io/');
        // $qrCodes['changeBgColor'] =
        //     QrCode::size(150)->backgroundColor(255, 0, 0)->generate('https://daffaraka.github.io/.io/');
        // $qrCodes['styleDot'] =
        //     QrCode::size(150)->style('dot')->generate('https://daffaraka.github.io/.io/');
        // $qrCodes['styleSquare'] = QrCode::size(150)->style('square')->generate('https://daffaraka.github.io/.io/');
        // $qrCodes['styleRound'] = QrCode::size(150)->style('round')->generate('https://daffaraka.github.io/.io/');


        // $qr = QrCode::format('png')
        //     ->size(300)
        //     ->generate('https://daffaraka.github.io/.io/');

        // return response()->make($qr, 200, [
        //     'Content-Type' => 'image/png',
        //     'Content-Disposition' => 'attachment; filename="qrcode.png"',
        // ]);

        // $data = $siswa->nis;


        // dd($base64Svg);
        // return view('dashboard.qrcode', compact('base64Svg'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nis' => 'required|numeric',
            'kelas' => 'required',
            'jk' => 'required',
        ]);

        $siswa = new \App\Models\Siswa;
        $siswa->nama = $request->nama;
        $siswa->nis = $request->nis;
        $siswa->kelas = $request->kelas;
        $siswa->jk = $request->jk;
        $siswa->save();

        return redirect()->route('siswa.index');
    }

    public function edit($id)
    {
        $siswa = \App\Models\Siswa::findOrFail($id);
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'nis' => 'required|numeric',
            'kelas' => 'required',
            'jk' => 'required',
        ]);

        $siswa = \App\Models\Siswa::findOrFail($id);
        $siswa->nama = $request->nama;
        $siswa->nis = $request->nis;
        $siswa->kelas = $request->kelas;
        $siswa->jk = $request->jk;
        $siswa->save();

        return redirect()->route('siswa.index');
    }

    public function destroy($id)
    {
        $siswa = \App\Models\Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('siswa.index');
    }


    public function import(Request $request)
    {


        // dd($request->all());
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new SiswaImport, $request->file('file'));


        return redirect()->back()->with('success', 'All good!');
    }
}
