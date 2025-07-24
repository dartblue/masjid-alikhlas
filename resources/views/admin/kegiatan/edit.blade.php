@extends('layouts.admin')
@section('title', 'Edit Kegiatan')
@section('content')
<div class="d-flex justify-content-between align-items-start mb-3 flex-wrap">
    <div>
        <h4 class="mb-1">Masjid Keandra Park Al-Ikhlas</h4>
        <h5 class="text-muted">Edit Kegiatan</h5>
    </div>
</div>
<div class="p-6">
    <form action="{{ route('admin.kegiatan.update', $kegiatan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="resumeadd-form">
            <div class="row">
                <div class="col-lg-6">
                    <span class="pf-title">Tanggal</span>
                    <div class="pf-field">
                        <input class="form-control datepicker" type="date" name="tanggal" value="{{ $kegiatan->tanggal }}" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="pf-title">Kegiatan</span>
                    <div class="pf-field">
                        <input class="form-control" type="text" name="kegiatan" value="{{ $kegiatan->kegiatan }}" required>
                    </div>
                </div>
                <div class="col-lg-12 mt-3">
                    <span class="pf-title">Gambar</span>
                    <div class="pf-field">
                        @if($kegiatan->gambar && file_exists(public_path('storage/' . $kegiatan->gambar)))
                            <img src="{{ asset('storage/' . $kegiatan->gambar) }}" alt="Gambar Kegiatan" width="150" class="mb-2">
                        @endif

                        <input class="form-control" type="file" name="gambar" id="gambar" accept="image/*">
                    </div>
                </div>
                <div class="col-lg-12 mt-3">
                    <span class="pf-title">Keterangan</span>
                    <div class="pf-field">
                        <textarea class="form-control" name="keterangan" id="keterangan" rows="6" required>{{ $kegiatan->keterangan }}</textarea>
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