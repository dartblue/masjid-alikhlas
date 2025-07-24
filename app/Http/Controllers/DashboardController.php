<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Penerimaan;
use App\Models\Pengeluaran;

class DashboardController extends Controller
{
    public function index(Request $request)
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

        return view('admin.dashboard', compact(
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
