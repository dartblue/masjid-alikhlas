@extends('layouts.admin')
@section('title', $artikel->judul)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Judul --}}
    <h2 class="text-3xl font-bold mb-2">{{ $artikel->judul }}</h2>

    {{-- Tanggal dan Kategori --}}
    <p class="text-gray-500 mb-4">
        {{ \Carbon\Carbon::parse($artikel->tanggal)->translatedFormat('M d, Y') }} | {{ $artikel->kategori }}
    </p>

    {{-- Gambar --}}
    @if($artikel->gambar)
        <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="rounded mb-6" style="width:70%;height:30%;">
    @endif

    {{-- Konten --}}
    <div class="prose max-w-none mt-4">
        {!! nl2br(e($artikel->konten)) !!}
    </div>
</div>
@endsection
