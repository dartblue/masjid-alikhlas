<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengeluaran Kas Masjid</title>
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
    <h4>Laporan Pengeluaran Kas Masjid</h4>
    <h4>Periode: {{ $periode }}</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Uraian</th>
                <th>Saldo Keluar</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_keluar = 0;
                $sisa = 0;
            @endphp
            @foreach($data as $i => $item)
                @php
                    $total_keluar += $item->saldo_keluar;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                    <td>{{ $item->uraian }}</td>
                    <td>{{ number_format($item->saldo_keluar, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Jumlah Pengeluaran</th>
                <th>{{ number_format($total_keluar, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
