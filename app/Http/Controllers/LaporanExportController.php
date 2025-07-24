<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerimaan;
use App\Models\Pengeluaran;
use PDF;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;


class LaporanExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        Carbon::setLocale('id');

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        if (!$bulan || !$tahun) return redirect()->back()->with('error', 'Bulan dan tahun wajib diisi');

        // Ambil data langsung dari model
        $penerimaan = Penerimaan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $pengeluaran = Pengeluaran::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        // Hitung total
        $totalPendapatan = $penerimaan->sum('saldo_masuk');
        $totalPengeluaran = $pengeluaran->sum('saldo_keluar');

        $pdf = PDF::loadView('exports.laporan_pdf', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'penerimaan' => $penerimaan,
            'pengeluaran' => $pengeluaran,
            'total_pendapatan' => $totalPendapatan,
            'total_pengeluaran' => $totalPengeluaran,
        ])->setPaper('A4', 'portrait');

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        return $pdf->download("laporan-keuangan-{$namaBulan}-{$tahun}.pdf");
    }

    public function exportExcel(Request $request)
    {
        Carbon::setLocale('id');
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        if (!$bulan || !$tahun) return redirect()->back()->with('error', 'Bulan dan tahun wajib diisi');

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        return Excel::download(new LaporanExport($bulan, $tahun), "laporan-keuangan-{$namaBulan}-{$tahun}.xlsx");
    }




}

