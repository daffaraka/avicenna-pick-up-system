<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PenjemputanHarian;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        if (Auth::user()->role == 'admin') {
            $data['greeting'] = 'Selamat datang di halaman dashboard';
            $data['penjemputan_hari_ini'] = PenjemputanHarian::where('created_at', Carbon::now()->format('Y-m-d'))->count();
        } else {


        }

        return view('dashboard.dashboard');
    }
}
