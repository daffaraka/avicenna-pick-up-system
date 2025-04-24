@extends('dashboard.layout')
@section('title', 'Data Penjemputan')
@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Siswa</h3>
        </div>
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Nama Penjemput</th>
                        <th>Jam Dijemput</th>
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
                            <td>{{ $penjemput->nama_penjemput }}</td>
                            <td>{{ $penjemput->jam_dijemput }}</td>
                            <td>{{ $penjemput->confirm_pic_at }}</td>
                            <td>{{ $penjemput->confirm_satpam_at }}</td>
                            <td>
                                {{-- <a href="{{ route('penjemputan.edit', $penjemput->id) }}"
                                    class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{ route('penjemputan.destroy', $penjemput->id) }}"
                                    class="btn btn-danger btn-sm">Hapus</a> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
