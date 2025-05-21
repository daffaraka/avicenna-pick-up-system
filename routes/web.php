<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\QRCodeGenerator;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjemputanHarianController;


Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::get('/create-post',[PostController::class,'create'])->name('post.create');
    Route::post('/store-post',[PostController::class,'store'])->name('post.store');

    Route::get('/notification',function(){

        $posts = Post::all();
        return view('frontend.notification',compact('posts'));
    });


    Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::get('qr-code',[QRCodeGenerator::class,'generate'])->name('qrcode');
    Route::get('generate-qr-code-siswa/{siswa}',[SiswaController::class,'generateQrCode'])->name('siswa.generateQrCode');
    Route::get('scan-qr',[QRCodeGenerator::class,'qrCodeScan'])->name('qrCodeScan');
    Route::post('scan-qr-code',[QRCodeGenerator::class,'scanQrCode'])->name('scan.scanQrCode');


    Route::resource('penjemputan-harian', PenjemputanHarianController::class)->except('show');
    Route::post('penjemput-datang',[PenjemputanHarianController::class,'penjemputDatang'])->name('penjemputDatang');


    Route::get('penjemputan-harian/satpam-konfirmasi-kedatangan/{penjemputan_harian}', [PenjemputanHarianController::class, 'satpamKonfirmasiKedatangan'])->name('penjemputan-harian.satpamKonfirmasiKedatangan');
    Route::get('penjemputan-harian/satpam-konfirmasi-keluar/{penjemputan_harian}', [PenjemputanHarianController::class, 'satpamKonfirmasiKeluar'])->name('penjemputan-harian.satpamKonfirmasiKeluar');
    Route::get('penjemputan-harian/guru-konfirmasi/{penjemputan_harian}', [PenjemputanHarianController::class, 'guruKonfirmasi'])->name('penjemputan-harian.guruKonfirmasi');
    Route::get('penjemputan-harian/{kelas}', [PenjemputanHarianController::class,'penjemputanKelas'])->name('penjemputan-harian.kelas');
    Route::get('generate-harian' , [PenjemputanHarianController::class,'generateSiswaHariIni'])->name('penjemputan-harian.generateSiswaHariIni');
    Route::post('penjemputan-harian/satpam-konfirmasi-ojol/', [PenjemputanHarianController::class, 'satpamKonfirmasiOjol'])->name('penjemputan-harian.satpamKonfirmasiOjol');
    Route::post('data-siswa/{id}', [PenjemputanHarianController::class, 'dataSiswa'])->name('penjemputan-harian.dataSiswa');
    Route::resource('siswa', SiswaController::class);
    Route::post('import-siswa', [SiswaController::class, 'import'])->name('siswa.import');

});




require __DIR__.'/auth.php';
