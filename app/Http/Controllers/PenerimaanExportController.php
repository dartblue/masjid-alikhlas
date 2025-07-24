<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenerimaanExport;
use Carbon\Carbon;

class PenerimaanExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        \Carbon\Carbon::setLocale('id');
        $bulan = $request->filled('bulan') ? (int) $request->input('bulan') : null;
        $tahun = $request->input('tahun', now()->year);

        $query = Penerimaan::query();

        if ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        }

        $query->whereYear('tanggal', $tahun);
        $data = $query->orderBy('tanggal', 'asc')->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Tidak ada data yang dapat diexport.');
        }

        $namaBulan = $bulan && $bulan >= 1 && $bulan <= 12
            ? Carbon::create()->month($bulan)->translatedFormat('F')
            : null;

        $periode = $namaBulan ? "$namaBulan $tahun" : "Tahun $tahun";
        $filename = 'laporan_penerimaan_periode_' . ($namaBulan ? $namaBulan . '_' : '') . $tahun . '.pdf';

        $pdf = Pdf::loadView('admin.kas.penerimaan_pdf', [
            'data' => $data,
            'periode' => $periode
        ]);

        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        \Carbon\Carbon::setLocale('id');
        $bulan = (int) $request->input('bulan');
        $tahun = (int) $request->input('tahun', now()->year);

        // Tambahkan validasi jika data kosong
        $query = \App\Models\Penerimaan::query();
        if ($bulan) $query->whereMonth('tanggal', $bulan);
        $query->whereYear('tanggal', $tahun);
        $data = $query->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Data tidak tersedia untuk periode tersebut.');
        }

        $namaBulan = $bulan ? \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') : null;
        $periode = $namaBulan ? "{$namaBulan}_{$tahun}" : "{$tahun}";
        $filename = "laporan_penerimaan_periode_{$periode}.xlsx";

        return Excel::download(new \App\Exports\PenerimaanExport($bulan, $tahun), $filename);
    }




}

