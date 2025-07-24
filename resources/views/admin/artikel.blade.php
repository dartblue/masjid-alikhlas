@extends('layouts.admin')

@section('title', 'Artikel')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3 flex-wrap">
    <div>
        <h4 class="mb-1">Masjid Keandra Park Al-Ikhlas</h4>
        <h5 class="text-muted">Data Artikel</h5>
    </div>
    <div class="d-flex flex-column align-items-end gap-2">
        <div class="d-flex align-items-center gap-2 mt-3">
            <a href="{{ route('admin.artikel.create') }}" class="btn btn-primary">+ Tambah Artikel</a>
        </div>
    </div>
</div>


<div class="table-responsive">
    <table class="table table-bordered text-center align-middle">
        <thead class="table-light text-center align-middle">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Gambar</th>
                <th>Pengunjung</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($artikels as $artikel)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($artikel->tanggal)->translatedFormat('d F Y') }}</td>
                <td>{{ \Illuminate\Support\Str::limit($artikel->judul, 20, '...') }}</td>
                <td>{{ $artikel->kategori }}</td>
                <td>
                    @if($artikel->gambar)
                        <img src="{{ asset('storage/' . $artikel->gambar) }}" width="80" class="rounded">
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>
                <td>{{ $artikel->kunjungan }}</td>
                <td>
                    <a href="{{ route('admin.artikel.lihat', $artikel->slug) }}" class="btn btn-sm btn-primary">Lihat</a>
                    <a href="{{ route('admin.artikel.edit', $artikel->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.artikel.destroy', $artikel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @if($artikels->isEmpty())
            <tr>
                <td colspan="6" class="text-center text-muted">Belum ada artikel.</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
