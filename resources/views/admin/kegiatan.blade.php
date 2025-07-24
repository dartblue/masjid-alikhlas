@extends('layouts.admin')

@section('title', 'Kegiatan')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3 flex-wrap">
    <div>
        <h4 class="mb-1">Masjid Keandra Park Al-Ikhlas</h4>
        <h5 class="text-muted">Data Kegiatan</h5>
    </div>
    <div class="d-flex flex-column align-items-end gap-2">
        <div class="d-flex align-items-center gap-2 mt-3">
            <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-primary">+ Tambah Kegiatan</a>
        </div>
    </div>

</div>


<div class="table-responsive">
    <table class="table table-bordered text-center align-middle">
        <thead class="table-light text-center align-middle">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kegiatan</th>
                <th>Gambar</th>
                <th>Pengunjung</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kegiatans as $kegiatan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}</td>
                <td>{{ $kegiatan->kegiatan }}</td>
                <td>
                    @if($kegiatan->gambar)
                        <img src="{{ asset('storage/' . $kegiatan->gambar) }}" width="80" class="rounded">
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>
                <td>{{ $kegiatan->kunjungan }}</td>
                <td>
                    <a href="{{ route('admin.kegiatan.lihat', $kegiatan->slug) }}" class="btn btn-sm btn-primary">Lihat</a>
                    <a href="{{ route('admin.kegiatan.edit', $kegiatan->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.kegiatan.destroy', $kegiatan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @if($kegiatans->isEmpty())
            <tr>
                <td colspan="6" class="text-center text-muted">Belum ada kegiatan.</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
