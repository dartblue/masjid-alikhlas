<table>
    <thead>
        <tr>
            <th colspan="8" align="center"><strong>Masjid Keandra Park Al-Ikhlas</strong></th>
        </tr>
        <tr>
            <th colspan="8" align="center"><strong>Laporan Penerimaan Donasi</strong></th>
        </tr>
        <tr>
            <th colspan="8" align="center">Periode: {{ $periode }}</th>
        </tr>
        <tr>
            <th align="center" rowspan="2"><strong>No</strong></th>
            <th align="center" rowspan="2"><strong>Tanggal</strong></th>
            <th align="center" rowspan="2"><strong>Uraian</strong></th>
            <th align="center" colspan="3"><strong>Jenis Penerimaan</strong></th>
            <th align="center" rowspan="2"><strong>Saldo Masuk</strong></th>
            <th align="center" rowspan="2"><strong>Sisa Saldo</strong></th>
        </tr>
        <tr>
            <th align="center"><strong>Tunai</strong></th>
            <th align="center"><strong>TF BCA</strong></th>
            <th align="center"><strong>TF BRI</strong></th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalTunai = 0; $totalBCA = 0; $totalBRI = 0;
            $totalMasuk = 0; $sisaAkhir = 0;
        @endphp
        @foreach($data as $i => $item)
        @php
            $tunai = $item->jenis === 'Tunai' ? $item->saldo_masuk : 0;
            $bca = $item->jenis === 'TF BCA' ? $item->saldo_masuk : 0;
            $bri = $item->jenis === 'TF BRI' ? $item->saldo_masuk : 0;
            
            $totalTunai += $tunai;
            $totalBCA += $bca;
            $totalBRI += $bri;

            $totalMasuk += $item->saldo_masuk;
            $sisaAkhir += $item->saldo_masuk;
        @endphp
            <tr>
                <td align="center">{{ $i + 1 }}</td>
                <td align="center">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                <td>{{ $item->uraian }}</td>
                <td align="right">{{ $tunai }}</td>
                <td align="right">{{ $bca }}</td>
                <td align="right">{{ $bri }}</td>
                <td align="right">{{ $item->saldo_masuk }}</td>
                <td align="right">{{ $sisaAkhir }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" align="center"><strong>Jumlah Penerimaan Donasi</strong></td>
            <td align="right">{{ $totalTunai }}</td>
            <td align="right">{{ $totalBCA }}</td>
            <td align="right">{{ $totalBRI }}</td>
            <td align="right">{{ $totalMasuk }}</td>
            <td align="right">{{ $sisaAkhir }}</td>
        </tr>
    </tfoot>
</table>
