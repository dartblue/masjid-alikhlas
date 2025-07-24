<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ArtikelController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');
        $artikels = Artikel::orderBy('tanggal', 'asc')->get();
        
        return view('admin.artikel', compact('artikels'));
    }

    public function create()
    {
        return view('admin.artikel.create');
    }

    public function lihat($slug)
    {
        $artikel = Artikel::where('slug', $slug)->firstOrFail();
        return view('admin.artikel.show', compact('artikel'));
    }

    public function show($slug)
    {
        $artikel = Artikel::where('slug', $slug)->firstOrFail();
        return view('components.detail_artikel', compact('artikel'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'kategori' => 'required|string',
            'kunjungan' => 'required|integer'
        ]);

        // Upload gambar
        $gambarPath = $request->file('gambar')->store('artikel', 'public');

        Artikel::create([
            'tanggal' => $request->tanggal,
            'judul' => $request->judul,
            'konten' => $request->konten,
            'kategori' => $request->kategori,
            'kunjungan' => $request->kunjungan,
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('admin.artikel.edit', compact('artikel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'judul' => 'required|string|max:255',
            'konten' => 'required',
            'kategori' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $artikel = Artikel::findOrFail($id);
        $artikel->tanggal = $request->tanggal;
        $artikel->judul = $request->judul;
        $artikel->konten = $request->konten;
        $artikel->kategori = $request->kategori;

        if ($request->hasFile('gambar')) {
            // Upload dengan nama terenkripsi
            $path = $request->file('gambar')->store('artikel', 'public');

            // Hapus gambar lama jika ada
            if ($artikel->gambar) {
                Storage::disk('public')->delete($artikel->gambar);
            }

            // Simpan path ke DB
            $artikel->gambar = $path;
        }

        $artikel->save();

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diupdate.');
    }

    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);

        // Hapus gambar dari storage
        if ($artikel->gambar) {
            Storage::disk('public')->delete($artikel->gambar);
        }

        $artikel->delete();

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }
}

?>
