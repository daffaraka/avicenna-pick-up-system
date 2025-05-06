@extends('dashboard.layout')
@section('title', 'Data Penjemputan')
@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mt-4">Penjemputan Hari Ini</h3>
        </div>
        <div class="card-body">

            <div class="d-flex justify-content-between px-3">
                <div class="h3">Data siswa dijemput kelas {{ Auth::user()->pic_kelas }}</div>

                <a href="{{route('penjemputan-harian.generateSiswaHariIni')}}" class="btn btn-info fw-bold text-white">Generate Hari Ini</a>
            </div>
            <div class="d-flex gap-2 mb-3 px-3">
                @foreach ($siswaDijemput as $key => $item)
                    <button type="button" class="btn btn-block btn-outline-primary fw-bold">{{ $key }}</button>
                @endforeach
            </div>
            <div class="table-responsive px-3">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            {{-- <th>Nama Penjemput</th> --}}
                            <th>Jam Penjemput Datang</th>
                            <th>Confirm Wali Kelas At</th>
                            <th>Confirm Satpam At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penjemputan as $penjemput)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $penjemput->siswa->nama_siswa }}</td>
                                <td>{{ $penjemput->siswa->kelas }}</td>
                                {{-- <td>{{ $penjemput->nama_penjemput }}</td> --}}
                                <td>{{ $penjemput->waktu_dijemput ? \Carbon\Carbon::parse($penjemput->waktu_dijemput)->isoFormat('H:m') : '-' }}
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
                                                <a href="" class="btn btn-primary fw-bold"><i class="ti-user"></i> Confirm
                                                    Kedatangan (Barcode/OJOL)</a>
                                            @break

                                            @case($penjemput->waktu_dijemput != null && $penjemput->confirm_pic_at == null)
                                                <a href="" class="btn btn-info text-light fw-bold"><i class="ti-car"></i>
                                                    Confirm Dijemput (Guru)</a>
                                            @break

                                            @case($penjemput->waktu_dijemput != null && $penjemput->confirm_pic_at != null)
                                                <a href="" class="btn btn-dark fw-bold"><i class="ti-export"></i> Confirm
                                                    Keluar (Security)</a>
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
@endsection

@push('scripts')
    <script>
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
            } else {
                console.log('Bukan kelas saya, alert tidak ditampilkan.');
            }
        });


        // Debugging line
        // pusher.connection.bind('connected', function() {
        //     console.log('Pusher connected');
        // });

        $(document).ready(function() {
            $('.table').DataTable();
        });
    </script>
@endpush
