<table>
    <tr>
        <td colspan="2"><strong>LAPORAN KEUANGAN</strong></td>
    </tr>
    <tr>
        <td colspan="2"><strong>Masjid Keandra Park Al-Ikhlas</strong></td>
    </tr>
    <tr>
        <td colspan="2">Periode {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</td>
    </tr>

    <tr><td colspan="2"></td></tr>

    <tr><td colspan="2"><strong>PENERIMAAN</strong></td></tr>
    @php
        $grouped = collect($penerimaan)->groupBy(fn($item) => \Carbon\Carbon::parse($item['tanggal'])->format('Y-m-d'));
    @endphp
    @foreach($grouped as $tgl => $items)
    <tr>
        <td>Donasi Warga tgl {{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}</td>
        <td>{{ collect($items)->sum('saldo_masuk') }}</td>
    </tr>
    @endforeach
    <tr>
        <td><strong>Total Penerimaan</strong></td>
        <td><strong>{{ $total_pendapatan }}</strong></td>
    </tr>

    <tr><td colspan="2"></td></tr>

    <tr><td colspan="2"><strong>PENGELUARAN</strong></td></tr>
    @foreach($pengeluaran as $item)
    <tr>
        <td>{{ $item['uraian'] }}</td>
        <td>{{ $item['jumlah'] }}</td>
    </tr>
    @endforeach
    <tr>
        <td><strong>Total Pengeluaran</strong></td>
        <td><strong>{{ $total_pengeluaran }}</strong></td>
    </tr>

    <tr><td colspan="2"></td></tr>

    <tr>
        <td><strong>Sisa Kas</strong></td>
        <td><strong>{{ $total_pendapatan - $total_pengeluaran }}</strong></td>
    </tr>
</table>
