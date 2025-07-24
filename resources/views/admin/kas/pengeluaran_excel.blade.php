<table>
    <thead>
        <tr>
            <th colspan="4" align="center"><strong>Masjid Keandra Park Al-Ikhlas</strong></th>
        </tr>
        <tr>
            <th colspan="4" align="center"><strong>Laporan Pengeluaran Kas Masjid</strong></th>
        </tr>
        <tr>
            <th colspan="4" align="center">Periode: {{ $periode }}</th>
        </tr>
        <tr>
            <th align="center"><strong>No</strong></th>
            <th align="center"><strong>Tanggal</strong></th>
            <th align="center"><strong>Uraian</strong></th>
            <th align="center"><strong>Saldo Keluar</strong></th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalKeluar = 0; $sisaAkhir = 0;
        @endphp
        @foreach($data as $i => $item)
        @php
            $totalKeluar += $item->saldo_keluar;
        @endphp
            <tr>
                <td align="center">{{ $i + 1 }}</td>
                <td align="center">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                <td>{{ $item->uraian }}</td>
                <td align="right">{{ $item->saldo_keluar }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" align="center"><strong>Jumlah Pengeluaran</strong></td>
            <td align="right">{{ $totalKeluar }}</td>
        </tr>
    </tfoot>
</table>
