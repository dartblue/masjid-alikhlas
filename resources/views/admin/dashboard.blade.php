@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3 flex-wrap">
    <div>
        <h4 class="mb-1">Masjid Keandra Park Al-Ikhlas</h4>
        <h5 class="text-muted">Dashboard Admin</h5>
    </div>
</div>
<div class="container">
    <div style="border: 2px solid gray; padding: 20px; border-radius: 10px;">
        <h4>Laporan Keuangan Masjid - Bulan {{ $bulanIndo }} {{ $tahun }}</h4>
        <h6>Saldo Awal {{ $bulanIndo }} {{ $tahun }} : Rp. {{ number_format($saldoAwal, 0, ',', '.') }}</h6>
        <hr>
        <h6 class="fw-bold">Penerimaan</h6>
        @php
            $groupedPenerimaan = collect($penerimaan)->groupBy(function($item) {
                return \Carbon\Carbon::parse($item['tanggal'])->format('Y-m-d');
            });
        @endphp
        @forelse($groupedPenerimaan as $tgl => $items)
            <span class="float-start">- Donasi warga tgl {{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}</span>
            <span class="float-end">Rp. {{ number_format(collect($items)->sum('saldo_masuk'), 0, ',', '.') }}</span><br>
        @empty
            <span class="float-start">Tidak ada data penerimaan</span><br>
        @endforelse
            <h6>
                <span class="float-start fw-bold mt-1">Total Penerimaan</span>
                <span class="float-end fw-bold mt-1">Rp. {{ number_format($totalPenerimaan, 0, ',', '.') }}</span>
            </h6><br>
        <h6 class="fw-bold">Pengeluaran</h6>
        @forelse($pengeluaran as $item)
            <span class="float-start">- {{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d F Y') }} {{ $item['uraian'] }}</span>
            <span class="float-end">Rp. {{ number_format($item['saldo_keluar'], 0, ',', '.') }}</span><br>
            </tr>
        @empty
            <span class="float-start">Tidak ada data pengeluaran</span><br>
        @endforelse
            <h6>
                <span class="float-start fw-bold mt-1">Total Pengeluaran</span>
                <span class="float-end fw-bold mt-1">Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
            </h6><br>
            <hr>
        <h6><span class="fw-bold float-start">Sisa Kas {{ $bulanIndo }} {{ $tahun }}</span> <span class="fw-bold float-end">Rp. {{ number_format($kasMasjid, 0, ',', '.') }}</span></h6><br><br>
    </div>
</div>
@endsection
