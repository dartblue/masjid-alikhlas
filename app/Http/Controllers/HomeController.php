<?php
namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Penerimaan;
use App\Models\Pengeluaran;
use App\Models\Artikel;
use App\Models\Kegiatan;

class HomeController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');
        $now = Carbon::now();
        $bulanIni = $now->month;
        $tahunIni = $now->year;
        $bulanLalu = $now->copy()->subMonth()->month;
        $tahunLalu = $now->copy()->subMonth()->year;

        // Total saldo bulan lalu = saldo awal bulan ini
        $totalPenerimaanLalu = Penerimaan::whereMonth('tanggal', $bulanLalu)->whereYear('tanggal', $tahunLalu)->sum('saldo_masuk');
        $totalPengeluaranLalu = Pengeluaran::whereMonth('tanggal', $bulanLalu)->whereYear('tanggal', $tahunLalu)->sum('saldo_keluar');
        $saldoAwal = $totalPenerimaanLalu - $totalPengeluaranLalu;

        // Data bulan ini
        $penerimaan = Penerimaan::whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->sum('saldo_masuk');
        $pengeluaran = Pengeluaran::whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->sum('saldo_keluar');
        $kasMasjid = $saldoAwal + $penerimaan - $pengeluaran;

        // Nama bulan dalam Bahasa Indonesia
        $namaBulan = $now->translatedFormat('F Y'); // contoh: Juli 2025
        $pengumuman = Artikel::where('kategori', 'Pengumuman')
                   ->orderBy('tanggal', 'asc')
                   ->take(3)
                   ->get();
        $artikelIslam = Artikel::where('kategori', 'Artikel Islam')
                    ->orderBy('tanggal', 'asc')
                    ->take(5)
                    ->get();
        $kegiatanMasjid = Kegiatan::orderBy('tanggal', 'asc')
                    ->take(5)
                    ->get();

        return view('home', compact('saldoAwal', 'penerimaan', 'pengeluaran', 'kasMasjid', 'namaBulan', 'artikelIslam', 'kegiatanMasjid', 'pengumuman'));
    }
    
    public function allData($kategori)
    {
        $kategori = strtolower($kategori); // biar aman
        Carbon::setLocale('id');
        $now = Carbon::now();
        $bulanIni = $now->month;
        $tahunIni = $now->year;
        $bulanLalu = $now->copy()->subMonth()->month;
        $tahunLalu = $now->copy()->subMonth()->year;

        // Total saldo bulan lalu = saldo awal bulan ini
        $totalPenerimaanLalu = Penerimaan::whereMonth('tanggal', $bulanLalu)->whereYear('tanggal', $tahunLalu)->sum('saldo_masuk');
        $totalPengeluaranLalu = Pengeluaran::whereMonth('tanggal', $bulanLalu)->whereYear('tanggal', $tahunLalu)->sum('saldo_keluar');
        $saldoAwal = $totalPenerimaanLalu - $totalPengeluaranLalu;

        // Data bulan ini
        $penerimaan = Penerimaan::whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->sum('saldo_masuk');
        $pengeluaran = Pengeluaran::whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->sum('saldo_keluar');
        $kasMasjid = $saldoAwal + $penerimaan - $pengeluaran;

        // Nama bulan dalam Bahasa Indonesia
        $namaBulan = $now->translatedFormat('F Y'); // contoh: Juli 2025
        $data = [];

        if ($kategori == 'pengumuman') {
            $judulHalaman = "Pengumuman";
            $data = Artikel::where('kategori', 'Pengumuman')
                        ->orderBy('tanggal', 'asc')->get();
        } elseif ($kategori == 'artikel') {
            $judulHalaman = "Artikel Islam";
            $data = Artikel::where('kategori', 'Artikel Islam')
                        ->orderBy('tanggal', 'asc')->get();
        } elseif ($kategori == 'kegiatan') {
            $judulHalaman = "Kegiatan Masjid";
            $data = Kegiatan::orderBy('tanggal', 'asc')->get();
        } else {
            abort(404); // kategori gak dikenal
        }

        return view('semua_data', compact('data', 'kategori', 'judulHalaman','saldoAwal', 'penerimaan', 'pengeluaran', 'kasMasjid', 'namaBulan'));
    }

}
