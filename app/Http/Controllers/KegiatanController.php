<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');
        $kegiatans = Kegiatan::orderBy('tanggal', 'asc')->get();
        
        return view('admin.kegiatan', compact('kegiatans'));
    }

    public function create()
    {
        return view('admin.kegiatan.create');
    }

    public function lihat($slug)
    {
        $kegiatan = Kegiatan::where('slug', $slug)->firstOrFail();
        return view('admin.kegiatan.show', compact('kegiatan'));
    }

    public function show($slug)
    {
        $kegiatan = Kegiatan::where('slug', $slug)->firstOrFail();
        return view('components.detail_kegiatan', compact('kegiatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'kunjungan' => 'required|integer'
        ]);

        // Upload gambar
        $gambarPath = $request->file('gambar')->store('kegiatan', 'public');

        Kegiatan::create([
            'tanggal' => $request->tanggal,
            'kegiatan' => $request->kegiatan,
            'keterangan' => $request->keterangan,
            'kunjungan' => $request->kunjungan,
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('admin.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string|max:255',
            'keterangan' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->tanggal = $request->tanggal;
        $kegiatan->kegiatan = $request->kegiatan;
        $kegiatan->keterangan = $request->keterangan;

        if ($request->hasFile('gambar')) {
            // Upload dengan nama terenkripsi
            $path = $request->file('gambar')->store('kegiatan', 'public');

            // Hapus gambar lama jika ada
            if ($kegiatan->gambar) {
                Storage::disk('public')->delete($kegiatan->gambar);
            }

            // Simpan path ke DB
            $kegiatan->gambar = $path;
        }

        $kegiatan->save();

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Hapus gambar dari storage
        if ($kegiatan->gambar) {
            Storage::disk('public')->delete($kegiatan->gambar);
        }

        $kegiatan->delete();

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}

?>
