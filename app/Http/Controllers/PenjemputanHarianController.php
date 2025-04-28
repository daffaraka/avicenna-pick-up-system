<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Events\TestNotification;
use App\Models\PenjemputanHarian;
use Illuminate\Support\Facades\Auth;

class PenjemputanHarianController extends Controller
{

    public function index()
    {

        // if (Auth::user()->role == 'Admin' && Auth::user()->role == 'Satpam') {
        //     $siswaDijemput = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
        //         ->with('siswa')
        //         ->get()
        //         ->groupBy('siswa.kelas')
        //         ->sortKeys();
        // $penjemputan = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))->with('siswa')->get();

        // } else {
        //     $siswaDijemput = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
        //     ->with('siswa')
        //     ->where('kelas', Auth::user()->pic_kelas)
        //     ->get()
        //     ->groupBy('siswa.kelas')
        //     ->sortKeys();

        // $penjemputan = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))->with('siswa')->get();

        // }

        $siswaDijemput = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->with('siswa')
            ->get()
            ->groupBy('siswa.kelas')
            ->sortKeys();

        $penjemputan = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->with('siswa')
            ->orderBy('waktu_dijemput', 'desc')
            ->get();
        return view('dashboard.penjemputan-harian.penjemputan-index', compact('penjemputan', 'siswaDijemput'));
    }

    public function create()
    {
        return view('penjemputan_harian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'field1' => 'required',
            'field2' => 'required',
            // Add other fields validation as needed
        ]);

        PenjemputanHarian::create($request->all());
        return redirect()->route('penjemputan-harian.index')
            ->with('success', 'Data created successfully.');
    }

    public function show(PenjemputanHarian $penjemputanHarian)
    {
        return view('penjemputan_harian.show', compact('penjemputanHarian'));
    }

    public function edit(PenjemputanHarian $penjemputanHarian)
    {
        return view('penjemputan_harian.edit', compact('penjemputanHarian'));
    }

    public function update(Request $request, PenjemputanHarian $penjemputanHarian)
    {
        $request->validate([
            'field1' => 'required',
            'field2' => 'required',
            // Add other fields validation as needed
        ]);

        $penjemputanHarian->update($request->all());
        return redirect()->route('penjemputan-harian.index')
            ->with('success', 'Data updated successfully.');
    }

    public function destroy(PenjemputanHarian $penjemputanHarian)
    {
        $penjemputanHarian->delete();
        return redirect()->route('penjemputan-harian.index')
            ->with('success', 'Data deleted successfully.');
    }


    public function penjemputDatang(PenjemputanHarian $penjemputanHarian)
    {

        event(new TestNotification([
            'nama_siswa' =>'Penjemput atas nama '. $penjemputanHarian->post. ' Sudah Datang',
        ]));


        $penjemputanHarian->update([
            'waktu_dijemput' => Carbon::now()
        ]);
        return response()->json([
            'success' => true,
            'data' => $penjemputanHarian->siswa->nama_siswa,
            'time' => Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y')
        ]);
    }



    public function satpamKonfirmasi(PenjemputanHarian $penjemputanHarian)
    {
        $penjemputanHarian->update([
            'confirm_satpam_at' => Carbon::now()
        ]);
        return redirect()->route('penjemputan-harian.index')
            ->with('success', 'Data updated successfully.');
    }


    public function guruKonfirmasi(PenjemputanHarian $penjemputanHarian)
    {
        $penjemputanHarian->update([
            'confirm_pic_at' => Carbon::now()
        ]);
        return redirect()->route('penjemputan-harian.index')
            ->with('success', 'Data updated successfully.');
    }


    public function generateSiswaHariIni()
    {
        $pic_kelas = Auth::user()->pic_kelas ?? array_rand(array_flip(range('1A', '3C')));
        $siswa_kelas = Siswa::where('kelas', $pic_kelas)->get();
        // $siswa = Siswa::all();

        foreach ($siswa_kelas as $siswa) {
            PenjemputanHarian::create([
                'pic_id' => $pic_kelas,
                'siswa_id' => $siswa->id,
            ]);
        }


        return response()->json(['success' => true, 'message' => 'Siswa Ditambahkan.']);
    }
}
