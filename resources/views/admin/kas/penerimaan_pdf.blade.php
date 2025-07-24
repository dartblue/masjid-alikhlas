<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penerimaan Donasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h3, h4 {
            margin: 0;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h3>Masjid Keandra Park Al-Ikhlas</h3>
    <h4>Laporan Penerimaan Donasi</h4>
    <h4>Periode: {{ $periode }}</h4>
    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Tanggal</th>
                <th rowspan="2">Uraian</th>
                <th colspan="3">Jenis Penerimaan</th>
                <th rowspan="2">Saldo Masuk</th>
                <th rowspan="2">Sisa Saldo</th>
            </tr>
            <tr>
                <th>Tunai</th>
                <th>TF BCA</th>
                <th>TF BRI</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_tunai = 0;
                $total_bca = 0;
                $total_bri = 0;
                $total_masuk = 0;
                $sisa = 0;
            @endphp
            @foreach($data as $i => $item)
                @php
                    $tunai = $item->jenis === 'Tunai' ? $item->saldo_masuk : 0;
                    $bca = $item->jenis === 'TF BCA' ? $item->saldo_masuk : 0;
                    $bri = $item->jenis === 'TF BRI' ? $item->saldo_masuk : 0;

                    $total_tunai += $tunai;
                    $total_bca += $bca;
                    $total_bri += $bri;

                    $total_masuk += $item->saldo_masuk;
                    $sisa += $item->saldo_masuk;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                    <td>{{ $item->uraian }}</td>
                    <td>{{ $tunai ? number_format($tunai, 0, ',', '.') : '-' }}</td>
                    <td>{{ $bca ? number_format($bca, 0, ',', '.') : '-' }}</td>
                    <td>{{ $bri ? number_format($bri, 0, ',', '.') : '-' }}</td>
                    <td>{{ number_format($item->saldo_masuk, 0, ',', '.') }}</td>
                    <td>{{ number_format($sisa, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Jumlah Penerimaan Donasi</th>
                <th>{{ number_format($total_tunai, 0, ',', '.') }}</th>
                <th>{{ number_format($total_bca, 0, ',', '.') }}</th>
                <th>{{ number_format($total_bri, 0, ',', '.') }}</th>
                <th>{{ number_format($total_masuk, 0, ',', '.') }}</th>
                <th>{{ number_format($sisa, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
