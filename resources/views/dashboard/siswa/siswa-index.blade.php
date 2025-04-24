@extends('dashboard.layout')
@section('title', 'Siswa')
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
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Foto</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswas as $siswa)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $siswa->nama_siswa }}</td>
                            <td>{{ $siswa->kelas }}</td>
                            <td><img src="{{ $siswa->foto }}" alt="Foto" class="img-thumbnail" width="50" height="50"></td>
                            <td>
                                <a href="{{route('siswa.generateQrCode',$siswa->id)}}" class="btn btn-info btn-sm">Qr Code</a>
                                <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{ route('siswa.destroy', $siswa->id) }}" class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
