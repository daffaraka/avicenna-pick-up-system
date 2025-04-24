<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\QRCodeGenerator;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjemputanHarianController;
use App\Http\Controllers\SiswaController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/create-post',[PostController::class,'create'])->name('post.create');
Route::post('/store-post',[PostController::class,'store'])->name('post.store');

Route::get('/notification',function(){

    $posts = \App\Models\Post::all();
    return view('frontend.notification',compact('posts'));
});


Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');
Route::get('qr-code',[QRCodeGenerator::class,'generate'])->name('qrcode');
Route::get('generate-qr-code-siswa/{siswa}',[SiswaController::class,'generateQrCode'])->name('siswa.generateQrCode');
Route::get('scan-qr',[QRCodeGenerator::class,'qrCodeScan'])->name('qrCodeScan');
Route::post('scan-qr-code',[QRCodeGenerator::class,'scanQrCode'])->name('scan.scanQrCode');


Route::resource('penjemputan-harian', PenjemputanHarianController::class);

Route::resource('siswa', SiswaController::class);
