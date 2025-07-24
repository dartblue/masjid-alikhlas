@extends('layouts.admin')
@section('title', 'Edit Artikel')
@section('content')
<div class="d-flex justify-content-between align-items-start mb-3 flex-wrap">
    <div>
        <h4 class="mb-1">Masjid Keandra Park Al-Ikhlas</h4>
        <h5 class="text-muted">Edit Artikel</h5>
    </div>
</div>
<div class="p-6">
    <form action="{{ route('admin.artikel.update', $artikel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="resumeadd-form">
            <div class="row">
                <div class="col-lg-6">
                    <span class="pf-title">Tanggal</span>
                    <div class="pf-field">
                        <input class="form-control datepicker" type="date" name="tanggal" value="{{ $artikel->tanggal }}" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="pf-title">Kategori</span>
                    <div class="pf-field">
                        <select name="kategori" id="kategori" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Artikel Islam" {{ $artikel->kategori == 'Artikel Islam' ? 'selected' : '' }}>Artikel Islam</option>
                            <option value="Pengumuman" {{ $artikel->kategori == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12 mt-3">
                    <span class="pf-title">Judul Artikel</span>
                    <div class="pf-field">
                        <input class="form-control" type="text" name="judul" value="{{ $artikel->judul }}" required>
                    </div>
                </div>
                <div class="col-lg-12 mt-3">
                    <span class="pf-title">Gambar</span>
                    <div class="pf-field">
                        @if($artikel->gambar && file_exists(public_path('storage/' . $artikel->gambar)))
                            <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="Gambar Artikel" width="150" class="mb-2">
                        @endif

                        <input class="form-control" type="file" name="gambar" id="gambar" accept="image/*">
                    </div>
                </div>
                <div class="col-lg-12 mt-3">
                    <span class="pf-title">Konten</span>
                    <div class="pf-field">
                        <textarea class="form-control" name="konten" id="konten" rows="6" required>{{ $artikel->konten }}</textarea>
                    </div>
                </div>
                <input class="form-control" type="hidden" name="kunjungan" value="0">
                <div class="d-flex flex-column align-items-end gap-2 mt-3">
                    <button class="btn btn-success" type="submit">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection