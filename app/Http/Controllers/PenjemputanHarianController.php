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

    public function index(Request $request)
    {

        if ($request->has('search')) {
        }

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


        // dd($penjemputan);
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


    // Metode scan barcode (Utama)
    public function penjemputDatang(Request $request)
    {

        $penjemputan = PenjemputanHarian::whereHas('siswa', function ($query) use ($request) {
            $query->where('nis', $request->data);
        })
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->first();



        if ($penjemputan) {
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
        } else {


            return response()->json([
                'success' => true,
                'data' => $request->data,
                'time' => Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y')
            ]);
        }

        // $penjemputan->update([
        //     'waktu_dijemput' => Carbon::now()
        // ]);


        // dispatch(new SendPenjemputanNotification($penjemputan->siswa));




        // return response()->json([
        //     'success' => true,
        //     'data' => $penjemputan->siswa->nama_siswa,
        //     'kelas' => $penjemputan->siswa->kelas,
        //     'time' => Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y')
        // ]);
    }





    // Jika penjemput lupa tap barcode, maka bisa menggunakan ini
    public function satpamKonfirmasiKedatangan(PenjemputanHarian $penjemputanHarian)
    {
        $penjemputanHarian->update([
            'waktu_dijemput' => Carbon::now()
        ]);

        dispatch(new SendPenjemputanNotification($penjemputanHarian->siswa));

        //
        return redirect()->route('penjemputan-harian.index')
            ->with('success', 'Satpam telah mengkonfirmasi.');
    }

    // Jika penjemput sudah keluar, maka memakai metode ini

    public function satpamKonfirmasiKeluar(PenjemputanHarian $penjemputanHarian)
    {

        if ($penjemputanHarian->waktu_dijemput == null) {
            return redirect()->route('penjemputan-harian.index')->with('error', 'Penjemput belum datang');
        }
        $penjemputanHarian->update([
            'confirm_satpam_at' => Carbon::now()
        ]);

        dispatch(new SendPenjemputanNotification($penjemputanHarian->siswa));

        return redirect()->route('penjemputan-harian.index')
            ->with('success', 'Atas nama ' + $penjemputanHarian->siswa->nama_siswa. ' sudah dikonfirmasi keluar oleh Admin.');
    }


    public function guruKonfirmasi(PenjemputanHarian $penjemputanHarian)
    {
        $penjemputanHarian->update([
            'confirm_pic_at' => Carbon::now()
        ]);

        dispatch(new SendPenjemputanNotification($penjemputanHarian->siswa));

        return redirect()->route('penjemputan-harian.index')
            ->with('success', 'Guru sudah konfirmasi atas nama ' + $penjemputanHarian->siswa->nama_siswa);
    }


    public function generateSiswaHariIni()
    {

        // $pic_kelas = Auth::user()->pic_kelas ?? array_rand(array_flip(range('1A', '3C')));

        try {
            if (Auth::user()->role != 'admin' && Auth::user()->role != 'satpam') {
                $siswa_kelas = Siswa::where('kelas', Auth::user()->pic_kelas)->get();
                foreach ($siswa_kelas as $siswa) {
                    PenjemputanHarian::create([
                        'pic_id' => Auth::user()->id,
                        'siswa_id' => $siswa->id,
                    ]);
                }
            } else {

                $siswa_kelas = Siswa::all();

                for ($i = 0; $i < count($siswa_kelas); $i++) {
                    PenjemputanHarian::create([
                        'pic_id' => Auth::user()->id,
                        'siswa_id' => $siswa_kelas[$i]->id,
                    ]);
                }
            }

            return redirect()->route('penjemputan-harian.index')
                ->with('success', 'Penjemputan hari ini berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('penjemputan-harian.index')
                ->with('error', 'Gagal menambahkan penjemputan. Silakan coba lagi.');
        }
        // return response()->json(['success' => true, 'message' => 'Siswa Ditambahkan.']);
    }


    public function dataSiswa(Request $request)
    {
        $siswa = PenjemputanHarian::with('siswa')->find($request->id);


        return response()->json([
            'success' => true,
            'data' => $siswa->siswa
        ]);
    }


    public function satpamKonfirmasiOjol(Request $request)
    {



        $penjemputan = PenjemputanHarian::whereHas('siswa', function ($query) use ($request) {
            $query->where('nis', $request->nis);
        })
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->first();
        // dd($penjemputan);

        if ($request->has('ojol')) {
            $penjemputan->update([
                'waktu_dijemput' => Carbon::now(),
                'type_ojol' => $request->type_ojol,
                'nama_ojol' => $request->nama_ojol,
                'plat_ojol' => $request->plat_ojol,
            ]);
        }


        dispatch(new SendPenjemputanNotification($penjemputan->siswa));

        //
        return redirect()->route('penjemputan-harian.index')
            ->with('success', 'Satpam telah mengkonfirmasi.');
    }


    public function refreshTablePenjemputan(Request $request)
    {
        // dd();
        if ($request->ajax()) {
            if (Auth::user()->role == 'admin' || Auth::user()->role == 'satpam') {
                $penjemputan = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
                    ->with('siswa')
                    ->orderBy('waktu_dijemput', 'desc')
                    ->get();
            } else {
                $penjemputan = PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))
                    ->with('siswa')
                    ->whereHas('siswa', function ($siswa) {
                        $siswa->where('kelas', Auth::user()->pic_kelas);
                    })
                    ->orderBy('waktu_dijemput', 'desc')
                    ->get();
            }

            return response()->json(['data' => $penjemputan, 'status' => 'success']);
        } else {
            return response()->json(['status' => 'no ajax']);
        }
    }





    public function nullPenjemputan()
    {
        try {
            PenjemputanHarian::whereDate('created_at', Carbon::now()->format('Y-m-d'))->update([
                'waktu_dijemput' => null
            ]);

            return redirect()->back()->with('success', 'Penjemputan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'Penjemputan gagal dihapus.');
        }
    }
}
