@extends('dashboard.layout')
@section('title', 'Dashboard')
@section('content')

    <div class="row align-items-stretch">
        <div class="col-8">
            <div class="card border shadow-sm rounded rounded-2 h-100">
                <div class="card-body">
                    <h4 class="card-title">Dashboard</h4>
                    <h6 class="card-subtitle">Selamat datang di halaman dashboard</h6>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border shadow-sm rounded rounded-2 h-100">
                <div class="card-body">
                    <h4 class="card-title">Aktivitas Terakhir</h4>

                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Rafi Maulana</strong><br>
                                <small class="fw-bold">Kelas: 5A</small>
                            </div>
                            <span class="badge badge-primary badge-pill">07:30 WIB</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Alya Rahma</strong><br>
                                <small class="fw-bold">Kelas: 4B</small>
                            </div>
                            <span class="badge badge-primary badge-pill">07:32 WIB</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Ilham Nugroho</strong><br>
                                <small class="fw-bold">Kelas: 6C</small>
                            </div>
                            <span class="badge badge-primary badge-pill">07:35 WIB</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Salsabila Aulia</strong><br>
                                <small class="fw-bold">Kelas: 3A</small>
                            </div>
                            <span class="badge badge-primary badge-pill">07:37 WIB</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Dio Putra</strong><br>
                                <small class="fw-bold">Kelas: 2B</small>
                            </div>
                            <span class="badge badge-primary badge-pill">07:40 WIB</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>




@endsection
