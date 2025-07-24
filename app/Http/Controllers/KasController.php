<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerimaan;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class KasController extends Controller
{
    public function index()
    {
        return view('admin.kas');
    }

    public function penerimaan()
    {
        return view('admin.kas.penerimaan');
    }

    public function pengeluaran()
    {
        return view('admin.kas.pengeluaran');
    }

    public function laporan()
    {
        return view('admin.kas.laporan');
    }

    public function detail(Request $request)
    {
        Carbon::setLocale('id');
        $now = Carbon::now();
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);
        $bulanLalu = $now->copy()->subMonth()->month;
        $tahunLalu = $now->copy()->subMonth()->year;

        // Total saldo bulan lalu = saldo awal bulan ini
        $totalPenerimaanLalu = Penerimaan::whereMonth('tanggal', $bulanLalu)->whereYear('tanggal', $tahunLalu)->sum('saldo_masuk');
        $totalPengeluaranLalu = Pengeluaran::whereMonth('tanggal', $bulanLalu)->whereYear('tanggal', $tahunLalu)->sum('saldo_keluar');
        $saldoAwal = $totalPenerimaanLalu - $totalPengeluaranLalu;

        $penerimaan = Penerimaan::whereMonth('tanggal', $bulan)
                            ->whereYear('tanggal', $tahun)
                            ->orderBy('tanggal', 'asc')->get();

        $pengeluaran = Pengeluaran::whereMonth('tanggal', $bulan)
                            ->whereYear('tanggal', $tahun)
                            ->orderBy('tanggal', 'asc')->get();

        $totalPenerimaan = $penerimaan->sum('saldo_masuk');
        $totalPengeluaran = $pengeluaran->sum('saldo_keluar');
        $kasMasjid = $saldoAwal + $totalPenerimaan - $totalPengeluaran;

        $bulanIndo = Carbon::create()->month($bulan)->translatedFormat('F');

        return view('components.detail_kas', compact(
            'saldoAwal',
            'penerimaan',
            'pengeluaran',
            'totalPenerimaan',
            'totalPengeluaran',
            'kasMasjid',
            'bulan',
            'tahun',
            'bulanIndo'
        ));
    }

}

?>
