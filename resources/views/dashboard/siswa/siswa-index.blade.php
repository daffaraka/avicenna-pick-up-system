@extends('dashboard.layout')
@section('title', 'Siswa')
@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Siswa</h3>
        </div>
        <div class="card-body">


            <button type="button" class="btn btn-info text-light shadow-sm mb-4" data-bs-toggle="modal"
                data-bs-target="#modal-import">
                Import Data Siswa
            </button>


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
                            <td><img src="{{ $siswa->foto }}" alt="Foto" class="img-thumbnail" width="50"
                                    height="50"></td>
                            <td>
                                <a href="{{ route('siswa.generateQrCode', $siswa->id) }}" class="btn btn-info btn-sm">Qr
                                    Code</a>
                                <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{ route('siswa.destroy', $siswa->id) }}" class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>



    <div class="modal fade" id="modal-import" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportLabel">Import Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih file untuk diimport</label>
                            <input type="file" class="form-control" id="file" name="file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                "pageLength": 25
            });
        });
    </script>
@endpush
