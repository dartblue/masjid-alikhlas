<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        h3, h4 {
            margin: 0;
        }
        .fw-bold { font-weight: bold; }
        .mt-4 { margin-top: 25px; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 4px 0;
            vertical-align: top;
        }
        .text-end {
            text-align: right;
        }
        .section {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <h3><strong>Masjid Keandra Park Al-Ikhlas</strong></h3>
    <h4>Laporan Penerimaan Donasi</h4>
    <h4>Periode {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</h4>

    {{-- PENERIMAAN --}}
    <div class="section">
        <h4 class="fw-bold">PENERIMAAN</h4>
        <table>
            @php
                $groupedPenerimaan = collect($penerimaan)->groupBy(function($item) {
                    return \Carbon\Carbon::parse($item['tanggal'])->format('Y-m-d');
                });
            @endphp
            @forelse($groupedPenerimaan as $tgl => $items)
                <tr>
                    <td>- Donasi warga tgl {{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}</td>
                    <td class="text-end">Rp {{ number_format(collect($items)->sum('saldo_masuk'), 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td class="text-muted">Tidak ada data penerimaan</td>
                    <td></td>
                </tr>
            @endforelse
            <tr class="fw-bold">
                <td>Total Penerimaan</td>
                <td class="text-end">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    {{-- PENGELUARAN --}}
    <div class="section">
        <h4 class="fw-bold">PENGELUARAN</h4>
        <table>
            @forelse($pengeluaran as $item)
                <tr>
                    <td>- {{ $item['uraian'] }}</td>
                    <td class="text-end">Rp {{ number_format($item['saldo_keluar'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td class="text-muted">Tidak ada data pengeluaran</td>
                    <td></td>
                </tr>
            @endforelse
            <tr class="fw-bold">
                <td>Total Pengeluaran</td>
                <td class="text-end">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    {{-- SISA KAS --}}
    <div class="section">
        <table>
            <tr class="fw-bold">
                <td>Sisa Kas {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</td>
                <td class="text-end">
                    {{ $total_pendapatan - $total_pengeluaran < 0 ? '-Rp ' : 'Rp ' }}
                    {{ number_format(abs($total_pendapatan - $total_pengeluaran), 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
