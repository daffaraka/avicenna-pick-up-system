<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Events\TestNotification;
use App\Models\PenjemputanHarian;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendPenjemputanNotification;

class PenjemputanHarianController extends Controller
{

    public function index()
    {


        if (Auth::user()->role == 'admin' || Auth::user()->role == 'satpam') {
            $siswaDijemput = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
                ->with('siswa')
                ->get()
                ->groupBy('siswa.kelas')
                ->sortKeys();
            $penjemputan = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
                ->with('siswa')
                ->orderBy('waktu_dijemput', 'desc')
                ->get();
        } else {
            $siswaDijemput = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
                ->with('siswa', function ($siswa) {
                    $siswa->where('kelas', Auth::user()->pic_kelas);
                })

                ->get()
                ->groupBy('siswa.kelas')
                ->sortKeys();

            $penjemputan = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
                ->with('siswa')
                ->whereHas('siswa', function ($siswa) {
                    $siswa->where('kelas', Auth::user()->pic_kelas);
                })
                ->orderBy('waktu_dijemput', 'desc')
                ->get();
        }




        // dd($penjemputan);

        // $siswaDijemput = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
        //     ->with('siswa')
        //     ->get()
        //     ->groupBy('siswa.kelas')
        //     ->sortKeys();

        // $penjemputan = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
        //     ->with('siswa')
        //     ->orderBy('waktu_dijemput', 'desc')
        //     ->get();
        return view('dashboard.penjemputan-harian.penjemputan-index', compact('penjemputan', 'siswaDijemput'));
    }


    public function penjemputanKelas($kelas)
    {

        $siswaDijemput = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->with('siswa')
            ->whereHas('siswa', function ($siswa) {
                $siswa->where('kelas', Auth::user()->pic_kelas);
            })
            ->get()
            ->groupBy('siswa.kelas')
            ->sortKeys();

        $penjemputan = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->with('siswa')
            ->whereHas('siswa', function ($siswa) {
                $siswa->where('kelas', Auth::user()->pic_kelas);
            })
            ->orderBy('waktu_dijemput', 'desc')
            ->get();


        // dd($siswaDijemput);
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


    public function penjemputDatang(Request $request)
    {

        $penjemputan = PenjemputanHarian::whereHas('siswa', function ($query) use ($request) {
            $query->where('nis', $request->data);
        })
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->first();

        $penjemputan->update([
            'waktu_dijemput' => Carbon::now()
        ]);


        dispatch(new SendPenjemputanNotification($penjemputan->siswa));




        return response()->json([
            'success' => true,
            'data' => $penjemputan->siswa->nama_siswa,
            'kelas' => $penjemputan->siswa->kelas,
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


        return redirect()->route('penjemputan-harian.index')
            ->with('success', 'Penjemputan hari ini berhasil ditambahkan.');
        // return response()->json(['success' => true, 'message' => 'Siswa Ditambahkan.']);
    }
}
