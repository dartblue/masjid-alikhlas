@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3 flex-wrap">
    <div>
        <h4 class="mb-1">Masjid Keandra Park Al-Ikhlas</h4>
        <h5 class="text-muted">Laporan Keuangan Masjid</h5>
    </div>
    <div class="d-flex flex-column align-items-end gap-2">
        <div class="d-flex gap-2 flex-wrap justify-content-end">
            <button class="btn btn-outline-danger" onclick="exportPdf()">Export PDF</button>
            <button onclick="exportExcel()" class="btn btn-outline-success">Export Excel</button>
        </div>

        <div class="d-flex align-items-center gap-2 mt-1">
            <label class="mb-0 fw-semibold">Periode</label>
            <select id="bulanFilter" class="form-select" style="width: 180px">
                <option value="">Pilih Bulan</option>
                @php
                    \Carbon\Carbon::setLocale('id');
                @endphp
                @foreach(range(1, 12) as $b)
                    <option value="{{ sprintf('%02d', $b) }}">{{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}</option>
                @endforeach
            </select>
            <select id="tahunFilter" class="form-select" style="width: 120px">
                @for($y = 2024; $y <= now()->year; $y++)
                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>
</div>
<div id="laporanContent" class="mt-4">
    <p class="text-muted">Silakan pilih bulan terlebih dahulu untuk melihat laporan.</p>
</div>
@endsection

@push('scripts')
<script>
function formatRupiah(angka) {
    angka = parseInt(angka || 0);
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
}

function formatTanggal(dateStr) {
    const tgl = new Date(dateStr);
    return `${tgl.getDate()} ${tgl.toLocaleString('id-ID', { month: 'long' })} ${tgl.getFullYear()}`;
}

function loadLaporan() {
    const bulan = document.getElementById('bulanFilter').value;
    const tahun = document.getElementById('tahunFilter').value;

    if (!bulan) {
        document.getElementById('laporanContent').innerHTML =
            '<p class="text-muted">Silakan pilih bulan terlebih dahulu untuk melihat laporan.</p>';
        return;
    }

    Promise.all([
        fetch(`/api/penerimaan?bulan=${bulan}&tahun=${tahun}`).then(res => res.json()),
        fetch(`/api/pengeluaran?bulan=${bulan}&tahun=${tahun}`).then(res => res.json())
    ])
    .then(([penerimaan, pengeluaran]) => {
        const totalPendapatan = penerimaan.total_masuk || 0;
        const totalPengeluaran = pengeluaran.total_keluar || 0;
        const sisaKas = totalPendapatan - totalPengeluaran;

        const namaBulan = new Date(`${tahun}-${bulan}-01`).toLocaleString('id-ID', { month: 'long' });

        const pendapatanByDate = {};
        penerimaan.data.forEach(item => {
            const tgl = formatTanggal(item.tanggal);
            if (!pendapatanByDate[tgl]) pendapatanByDate[tgl] = 0;
            pendapatanByDate[tgl] += item.saldo_masuk;
        });

        const pendapatanList = Object.entries(pendapatanByDate).map(([tgl, total]) =>
            `<li>- Donasi warga tgl ${tgl}<span class="float-end">${formatRupiah(total)}</span></li>`
        ).join('');

        const pengeluaranList = pengeluaran.data.map(item =>
            `<li>- ${item.uraian}<span class="float-end">${formatRupiah(item.saldo_keluar)}</span></li>`
        ).join('');

        document.getElementById('laporanContent').innerHTML = `
            <h5 class="fw-bold mt-4">PENERIMAAN</h5>
            <ul class="list-unstyled">
                ${pendapatanList || '<li class="text-muted">Tidak ada data penerimaan.</li>'}
            </ul>
            <span class="fw-bold float-start">Total Penerimaan</span> <span class="fw-bold float-end">${formatRupiah(totalPendapatan)}</span><br>

            <h5 class="fw-bold mt-4">PENGELUARAN</h5>
            <ul class="list-unstyled">
                ${pengeluaranList || '<li class="text-muted">Tidak ada data pengeluaran.</li>'}
            </ul>
            <span class="fw-bold float-start">Total Pengeluaran</span> <span class="fw-bold float-end">${formatRupiah(totalPengeluaran)}</span><br><br>

            <h5><span class="fw-bold float-start">Sisa Kas ${namaBulan} ${tahun}</span> <span class="fw-bold float-end">${formatRupiah(sisaKas)}</span></h5>
        `;
    })
    .catch(err => {
        console.error('Gagal memuat laporan:', err);
        document.getElementById('laporanContent').innerHTML =
            '<p class="text-danger">Gagal memuat data laporan.</p>';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('bulanFilter').addEventListener('change', loadLaporan);
    document.getElementById('tahunFilter').addEventListener('change', loadLaporan);
});
</script>
<script>
function exportPdf() {
    const bulan = document.getElementById('bulanFilter').value;
    const tahun = document.getElementById('tahunFilter').value;
    if (!bulan || !tahun) {
        alert('Pilih bulan dan tahun terlebih dahulu.');
        return;
    }
    window.location.href = `/export/laporan/pdf?bulan=${bulan}&tahun=${tahun}`;
}

function exportExcel() {
    const bulan = document.getElementById('bulanFilter').value;
    const tahun = document.getElementById('tahunFilter').value;
    if (!bulan || !tahun) {
        alert('Pilih bulan dan tahun terlebih dahulu.');
        return;
    }
    window.location.href = `/export/laporan/excel?bulan=${bulan}&tahun=${tahun}`;
}
</script>
@endpush
