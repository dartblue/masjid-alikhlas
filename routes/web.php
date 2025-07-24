<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\KegiatanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('home');
});

Route::get('/jadwal-sholat', function () {
    $response = Http::get('https://muslimsalat.com/cirebon.json?key=6551d7f9c40020a405e910202b2091d2');

    if ($response->ok()) {
        return $response->json();
    } else {
        return response()->json(['error' => 'Gagal mengambil jadwal'], 500);
    }
});

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/{kategori}', [HomeController::class, 'allData']);
Route::get('/kas-masjid', [KasController::class, 'detail'])->name('components.detail_kas');
Route::get('/artikel/{slug}', [ArtikelController::class, 'show'])->name('artikel.show');
Route::get('/pengumuman/{slug}', [ArtikelController::class, 'show'])->name('pengumuman.show');
Route::get('/kegiatan/{slug}', [KegiatanController::class, 'show'])->name('kegiatan.show');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [DashboardController::class, 'index']);
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/kas', [KasController::class, 'index'])->name('admin.kas');
    Route::get('/admin/artikel', [ArtikelController::class, 'index'])->name('admin.artikel');
    Route::get('/admin/kegiatan', [KegiatanController::class, 'index'])->name('admin.kegiatan');
    Route::get('/admin/artikel/lihat/{slug}', [ArtikelController::class, 'lihat'])->name('admin.artikel.lihat');
    Route::get('/admin/kegiatan/lihat/{slug}', [KegiatanController::class, 'lihat'])->name('admin.kegiatan.lihat');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('artikel', ArtikelController::class);
    });
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('kegiatan', KegiatanController::class);
    });
});

Route::middleware('auth')->prefix('admin/kas')->group(function () {
    Route::get('/penerimaan', [KasController::class, 'penerimaan'])->name('kas.penerimaan');
    Route::get('/pengeluaran', [KasController::class, 'pengeluaran'])->name('kas.pengeluaran');
    Route::get('/laporan', [KasController::class, 'laporan'])->name('kas.laporan');
});

use App\Http\Controllers\PenerimaanExportController;

Route::get('/export/penerimaan/pdf', [PenerimaanExportController::class, 'exportPdf'])->name('penerimaan.export.pdf');
Route::get('/export/penerimaan/excel', [PenerimaanExportController::class, 'exportExcel'])->name('penerimaan.export.excel');

use App\Http\Controllers\PengeluaranExportController;

Route::get('/export/pengeluaran/pdf', [PengeluaranExportController::class, 'exportPdf'])->name('pengeluaran.export.pdf');
Route::get('/export/pengeluaran/excel', [PengeluaranExportController::class, 'exportExcel'])->name('pengeluaran.export.excel');

use App\Http\Controllers\LaporanExportController;

Route::get('/export/laporan/pdf', [LaporanExportController::class, 'exportPdf'])->name('laporan.export.pdf');
Route::get('/export/laporan/excel', [LaporanExportController::class, 'exportExcel'])->name('laporan.export.excel');