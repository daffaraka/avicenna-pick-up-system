@extends('dashboard.layout')
@section('title', 'Data Penjemputan')
@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mt-4">Penjemputan Hari Ini</h3>
        </div>
        <div class="card-body">

            <div class="d-flex gap-2 mb-3 px-3">
                @foreach ($siswaDijemput as $key => $item)
                    {{-- <div class="col-1"> --}}
                    <button type="button" class="btn btn-block btn-outline-primary fw-bold">{{ $key }}</button>
                    {{-- </div> --}}
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
                            <th>Confirm PIC At</th>
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
                                                    Kedatangan</a>
                                            @break

                                            @case($penjemput->waktu_dijemput != null && $penjemput->confirm_pic_at == null)
                                                <a href="" class="btn btn-info text-light fw-bold"><i class="ti-car"></i>
                                                    Confirm Dijemput</a>
                                            @break

                                            @case($penjemput->waktu_dijemput != null && $penjemput->confirm_pic_at != null)
                                                <a href="" class="btn btn-dark fw-bold"><i class="ti-export"></i> Confirm
                                                    Keluar</a>
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
    Pusher.logToConsole = true;

    // Initialize Pusher
    var pusher = new Pusher('76a2a7e56f5027ca66a4', {
        cluster: 'mt1'
    });

    // Subscribe to the channel
    var channel = pusher.subscribe('apus-notification');



    channel.bind('notifikasi-penjemputan', function(data) {
        console.log('Received data:', data); // Debugging line

        // Display Toastr notification with icons and inline content
        if (data) {

            // alert(`New Post Notification:  ${data.post} `);

            // alert(`New Post Notification:  ${data.post}  ${data.desc}`);
            swal({
                title: 'Penjemputan Terbaru',
                content: $('<div>')
                    .addClass('notification-content')
                    .append(`<span style="margin-left: 20px;">${data.nama_siswa}</span>`)[0],
                icon: 'info',
                button: {
                    text: 'Close',
                    closeModal: true
                },
                className: 'swal-toast',
                timer: null // Toast persists until closed
            });

            // Update the notification container without reloading the page
            $('#notification-container').html('');
            $.each(data.posts, function(index, post) {
                $('#notification-container').append(
                    `<button class="btn btn-primary">${post.nama_siswa}</button>`);
            });
        } else {
            console.error('Invalid data received:', data);
        }
    });

    // Debugging line
    pusher.connection.bind('connected', function() {
        console.log('Pusher connected');
    });

    $(document).ready(function() {
        $('.table').DataTable();
    });
</script>

@endpush
