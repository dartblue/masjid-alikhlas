@extends('layouts.admin')
@section('title', $kegiatan->kegiatan)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Judul --}}
    <h2 class="text-3xl font-bold mb-2">{{ $kegiatan->kegiatan }}</h2>

    {{-- Tanggal dan Kategori --}}
    <p class="text-gray-500 mb-4">
        {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('M d, Y') }} | Masjid Al-Ikhlas
    </p>

    {{-- Gambar --}}
    @if($kegiatan->gambar)
        <img src="{{ asset('storage/' . $kegiatan->gambar) }}" alt="{{ $kegiatan->kegiatan }}" class="rounded mb-6" style="width:70%;height:30%;">
    @endif

    {{-- Konten --}}
    <div class="prose max-w-none mt-4">
        {!! nl2br(e($kegiatan->keterangan)) !!}
    </div>
</div>
@endsection
