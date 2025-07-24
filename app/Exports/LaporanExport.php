<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Penerimaan;
use App\Models\Pengeluaran;

class LaporanExport implements FromView
{
    protected $bulan, $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $penerimaan = Penerimaan::whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->get();

        $pengeluaran = Pengeluaran::whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->get();

        return view('exports.laporan_excel', [
            'penerimaan' => $penerimaan,
            'pengeluaran' => $pengeluaran,
            'total_pendapatan' => $penerimaan->sum('saldo_masuk'),
            'total_pengeluaran' => $pengeluaran->sum('saldo_keluar'),
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }
}
