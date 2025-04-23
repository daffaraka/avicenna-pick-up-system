<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/create-post',[PostController::class,'create'])->name('post.create');
Route::post('/store-post',[PostController::class,'store'])->name('post.store');

Route::get('/notification',function(){

    $posts = \App\Models\Post::all();
    return view('frontend.notification',compact('posts'));
});
