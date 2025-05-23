@extends('dashboard.layout')
@section('title', 'Data Penjemputan')
@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mt-4">Penjemputan Hari Ini</h3>
        </div>
        <div class="card-body">

            <div class="d-xl-flex justify-content-between px-3">
                <div class="h3">Data siswa dijemput kelas {{ Auth::user()->pic_kelas }}</div>



                <div class="d-flex my-lg-2 gap-2">
                    @env('local')
                    <a href="{{ route('penjemputan-harian.nullPenjemputan') }}" class="btn btn-danger fw-bold text-white"
                        id="btnNullPenjemputan">Null
                        Penjemputan</a>
                    @endenv

                    <a href="{{ route('penjemputan-harian.generateSiswaHariIni') }}"
                        class="btn btn-info fw-bold text-white">Generate Hari Ini</a>
                </div>

            </div>
            <div class="d-flex gap-2 mb-3 px-3">


                {{-- @foreach ($siswaDijemput as $key => $item)
                    <button type="button" class="btn btn-block btn-outline-primary fw-bold">{{ $key }}</button>
                @endforeach --}}
            </div>
            <div class="table-responsive px-3">
                <table id="penjemputan-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            {{-- <th>Nama Penjemput</th> --}}
                            <th>Jam Penjemput <br> Datang</th>
                            <th>Confirm Wali <br> Kelas At</th>
                            <th>Confirm <br> Satpam At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penjemputan as $penjemput)
                            <tr
                                class="@switch($penjemput)
                                @case($penjemput->waktu_dijemput == null)
                                    bg-warning
                                @break
                                @case($penjemput->waktu_dijemput != null && $penjemput->confirm_pic_at == null)
                                    bg-info
                                @break
                                @case($penjemput->waktu_dijemput != null && $penjemput->confirm_pic_at != null && $penjemput->confirm_satpam_at == null)
                                    bg-dark
                                @break
                                @case($penjemput->waktu_dijemput != null && $penjemput->confirm_pic_at != null && $penjemput->confirm_satpam_at != null)
                                    bg-success
                                @break
                            @endswitch">

                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $penjemput->siswa->nama_siswa ?? '-' }}</td>
                                <td>{{ $penjemput->siswa->kelas ?? '-' }}</td>
                                {{-- <td>{{ $penjemput->nama_penjemput }}</td> --}}
                                <td>{{ $penjemput->waktu_dijemput ? \Carbon\Carbon::parse($penjemput->waktu_dijemput)->isoFormat('H:mm') : '-' }}
                                </td>
                                <td>{{ $penjemput->confirm_pic_at ? \Carbon\Carbon::parse($penjemput->confirm_pic_at)->isoFormat('H:m') : '-' }}
                                </td>
                                <td>{{ $penjemput->confirm_satpam_at ? \Carbon\Carbon::parse($penjemput->confirm_satpam_at)->isoFormat('H:m') : '-' }}
                                </td>

                                {{-- <td>{{ \Carbon\Carbon::parse($penjemput->waktu_dijemput)->locale('id_ID')->isoFormat('H:s dddd, d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($penjemput->confirm_pic_at)->locale('id_ID')->isoFormat('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($penjemput->confirm_satpam_at)->locale('id_ID')->isoFormat('d/m/Y') }}</td> --}}
                                <td>
                                    <div class="d-flex gap-2">
                                        @switch($penjemput)
                                            @case($penjemput->waktu_dijemput == null)
                                                @if (Auth::user()->role == 'satpam' || Auth::user()->role == 'admin')
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary fw-bold dropdown-toggle" type="button"
                                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti-user"></i> Konfirmasi Kedatangan
                                                        </button>
                                                        <div class="dropdown-menu w-100 bg-light"
                                                            aria-labelledby="dropdownMenuButton">
                                                            <a href="{{ route('penjemputan-harian.satpamKonfirmasiKedatangan', $penjemput->id) }}"
                                                                class="dropdown-item fw-bold py-3" href="#">Konfirmasi
                                                                Sekarang</a>
                                                            <button type="button" data-id="{{ $penjemput->id }}"
                                                                data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                                                class="dropdown-item fw-bold py-3 penjemputanButton"
                                                                href="#">Penjemput Lain</button>
                                                        </div>
                                                    </div>
                                                @else
                                                    <a href="#" class="btn btn-warning fw-bold disabled"><i
                                                            class="ti-alert"></i> Satpam Belum
                                                        Konfirmasi</a>
                                                @endif
                                            @break

                                            @case($penjemput->waktu_dijemput != null && $penjemput->confirm_pic_at == null)
                                                <a href="{{ route('penjemputan-harian.guruKonfirmasi', $penjemput->id) }}"
                                                    class="btn btn-info text-light fw-bold"><i class="ti-car"></i>
                                                    Konfirmasi Dijemput (Guru)</a>
                                            @break

                                            @case($penjemput->waktu_dijemput != null && $penjemput->confirm_pic_at != null && $penjemput->confirm_satpam_at == null)
                                                @if (Auth::user()->role == 'satpam' || Auth::user()->role == 'admin')
                                                    <a href="{{ route('penjemputan-harian.satpamKonfirmasiKeluar', $penjemput->id) }}"
                                                        class="btn btn-dark fw-bold"><i class="ti-export"></i> Siswa Sudah
                                                        Keluar Area(Security)</a>
                                                @else
                                                    <a href="#" class="btn btn-warning fw-bold disabled"><i
                                                            class="ti-alert"></i> Satpam Belum Dikonfirmasi Keluar</a>
                                                @endif
                                            @break

                                            @case($penjemput->waktu_dijemput != null && $penjemput->confirm_pic_at != null && $penjemput->confirm_satpam_at != null)
                                                <a href="#" class="btn btn-success fw-bold disabled"><i
                                                        class="ti-check-box"></i> Siswa
                                                    Sudah Pulang</a>
                                            @break
                                        @endswitch

                                    </div>


                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>


    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">

        <form action="{{ route('penjemputan-harian.satpamKonfirmasiOjol') }}" method="POST">
            @csrf

            <input type="hidden" name="penjemputan_id" id="penjemputan_id" value="">
            <input type="hidden" name="nis" id="nis" value="">

            <input type="hidden" name="ojol" value="ojol">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_siswa" class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="kelas" name="kelas" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="type_ojol" class="form-label">Pilih Penjemput</label>
                            <select class="form-select text-dark" id="type_ojol" name="type_ojol" required>
                                <option value="">Pilih</option>
                                <option value="Kerabat&Keluarga">Kerabat/Keluarga</option>
                                <option value="Ojol">Ojek Online</option>
                                <option value="Taxi Online">Taxi Online </option>
                                {{-- <option value="go-ride">Go-ride</option>
                                <option value="go-car">Go-car</option>
                                <option value="grab-bike">Grab-bike</option>
                                <option value="grab-car">Grab-car</option>
                                <option value="maxim">Maxim</option> --}}
                            </select>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="nama_penjemput" class="form-label">Nama Ojol Penjemput</label>
                            <input type="text" class="form-control" id="nama_penjemput" name="nama_penjemput"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="plat_nomor" class="form-label">Plat Nomor</label>
                            <input type="text" class="form-control" id="plat_nomor" name="plat_nomor" required>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>

    <script>
        // Auto-refresh setiap 5 detik
        setInterval(refreshPenjemputanTable, 5000);


        function refreshPenjemputanTable() {
            let userRole = document.querySelector('meta[name="user-role"]').content;
            console.log('meta user role : ' + userRole);

            $.ajax({
                url: "{{ route('penjemputan-harian.refreshTablePenjemputan') }}",
                type: "get",
                success: function(response) {
                    if (response.status === 'success') {
                        $('#penjemputan-table').DataTable().destroy();
                        $('#penjemputan-table tbody').empty();

                        const data = response.data;

                        data.forEach((penjemput, index) => {
                            let rowClass = '';
                            if (!penjemput.waktu_dijemput) {
                                rowClass = 'bg-warning';
                            } else if (penjemput.waktu_dijemput && !penjemput
                                .confirm_pic_at) {
                                rowClass = 'bg-info';
                            } else if (penjemput.waktu_dijemput && penjemput
                                .confirm_pic_at && !penjemput.confirm_satpam_at) {
                                rowClass = 'bg-dark';
                            } else if (penjemput.waktu_dijemput && penjemput
                                .confirm_pic_at && penjemput.confirm_satpam_at) {
                                rowClass = 'bg-success';
                            }

                            const formatJam = waktu => waktu ? dayjs(waktu).format(
                                'HH:mm') : '-';

                            let aksiHTML = '';

                            if (!penjemput.waktu_dijemput) {
                                if (userRole === 'satpam' || userRole === 'admin') {
                                    aksiHTML = `
            <div class="dropdown">
                <button class="btn btn-primary fw-bold dropdown-toggle" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti-user"></i> Konfirmasi Kedatangan
                </button>
                <div class="dropdown-menu w-100 bg-light">
                    <a href="/penjemputan-harian/satpam-konfirmasi-kedatangan/${penjemput.id}" class="dropdown-item fw-bold py-3">Konfirmasi Sekarang</a>
                    <button type="button" data-id="${penjemput.id}" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="dropdown-item fw-bold py-3 penjemputanButton">Penjemput Lain</button>
                </div>
            </div>`;
                                } else {
                                    aksiHTML =
                                        `<a href="#" class="btn btn-warning fw-bold disabled"><i class="ti-alert"></i> Satpam Belum Konfirmasi</a>`;
                                }

                            } else if (penjemput.waktu_dijemput && !penjemput.confirm_pic_at) {
                                aksiHTML = `<a href="/penjemputan-harian/guru-konfirmasi/${penjemput.id}" class="btn btn-info text-light fw-bold">
                    <i class="ti-car"></i> Konfirmasi Dijemput (Guru)
                </a>`;

                            } else if (penjemput.waktu_dijemput && penjemput.confirm_pic_at && !
                                penjemput.confirm_satpam_at) {
                                if (userRole === 'satpam' || userRole === 'admin') {
                                    aksiHTML = `<a href="/penjemputan-harian/satpam-konfirmasi-keluar/${penjemput.id}" class="btn btn-dark fw-bold">
                        <i class="ti-export"></i> Siswa Sudah Keluar Area(Security)
                    </a>`;
                                } else {
                                    aksiHTML =
                                        `<a href="#" class="btn btn-warning fw-bold disabled"><i class="ti-alert"></i> Satpam Belum Dikonfirmasi Keluar</a>`;
                                }

                            } else if (penjemput.waktu_dijemput && penjemput.confirm_pic_at && penjemput
                                .confirm_satpam_at) {
                                aksiHTML = `<a href="#" class="btn btn-success fw-bold disabled">
                    <i class="ti-check-box"></i> Siswa Sudah Pulang
                </a>`;
                            }

                            const rowHTML = `
                                <tr class="${rowClass}">
                                    <td>${index + 1}</td>
                                    <td>${penjemput.siswa?.nama_siswa ?? '-'}</td>
                                    <td>${penjemput.siswa?.kelas ?? '-'}</td>
                                    <td>${formatJam(penjemput.waktu_dijemput)}</td>
                                    <td>${formatJam(penjemput.confirm_pic_at)}</td>
                                    <td>${formatJam(penjemput.confirm_satpam_at)}</td>
                                    <td><div class="d-flex gap-2">${aksiHTML}</div></td>
                                </tr>
                            `;

                            $('#penjemputan-table tbody').append(rowHTML);
                        });

                        $('#penjemputan-table').DataTable();
                    }
                }
            });
        }

        var picKelas = @json(Auth::user()->pic_kelas);


        Pusher.logToConsole = true;

        // Initialize Pusher
        var pusher = new Pusher('76a2a7e56f5027ca66a4', {
            cluster: 'mt1'
        });

        // Subscribe to the channel
        var channel = pusher.subscribe('apus-notification');



        channel.bind('notifikasi-penjemputan', function(data) {
            console.log('Received data:', data);
            console.log('Kelas : ' + data.kelas);
            console.log('picKelas user : ' + picKelas);

            if (data && data.kelas === picKelas) {

                swal({
                    title: 'Penjemputan Terbaru',
                    content: $('<div>')
                        .addClass('notification-content')
                        .append(`<span style="margin-left: 20px;">${data.notifikasi}</span>`)[0],
                    icon: 'info',
                    button: {
                        text: 'Close',
                        closeModal: true
                    },
                    className: 'swal-toast',
                    timer: null
                });

                $('#notification-container').html('');
                $.each(data.posts, function(index, post) {
                    $('#notification-container').append(
                        `<button class="btn btn-primary">${post.nama_siswa}</button>`
                    );
                });

                // $('#penjemputan-table').DataTable().ajax.reload();



                // Jalankan saat halaman dimuat
                refreshPenjemputanTable();

                // Auto-refresh setiap 5 detik
                setInterval(refreshPenjemputanTable, 5000);

            } else {
                console.log('Bukan kelas saya, alert tidak ditampilkan.');
            }
        });




        $(document).ready(function() {

            $('.penjemputanButton').click(function(e) {
                e.preventDefault();
                var penjemputan_id = $(this).data('id');
                $('#penjemputan_id').val(penjemputan_id);
                $.ajax({
                    type: "post",
                    url: `/data-siswa/${penjemputan_id}`,
                    data: {
                        id: penjemputan_id,
                        _token: '{{ csrf_token() }}'
                    },

                    dataType: "json",
                    success: function(response) {
                        $('#penjemputan_id').val(penjemputan_id);
                        $('#nis').val(response.data.nis);
                        $('#nama_siswa').val(response.data.nama_siswa);
                        $('#kelas').val(response.data.kelas);
                    }
                });
            });

            $('.table').DataTable();
        });



        $('#btnNullPenjemputan').click(function() {
            $.ajax({
                url: "{{ route('penjemputan-harian.nullPenjemputan') }}",
                type: 'GET',
                data: {
                    _token: '{{ csrf_token() }}'
                }
            }).done(function(response) {
                console.log(response);
            });
        });
    </script>
@endpush
