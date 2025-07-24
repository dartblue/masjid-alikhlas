<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Models\Pengeluaran;
    use Illuminate\Support\Facades\DB;

    class PengeluaranApiController extends Controller
    {
        
        public function index(Request $request)
        {
            $bulan = $request->get('bulan'); // format 01, 02, ..., 12
            $tahun = $request->get('tahun') ?? date('Y');

            $query = Pengeluaran::query();

            if ($bulan) {
                $query->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun);
            }

            $data = $query->orderBy('tanggal', 'asc')->get();

            $sisa = 0;

            $formatted = $data->map(function ($item) use (&$sisa) {
                $sisa += $item->saldo_keluar;

                return [
                    'id' => $item->id,
                    'tanggal' => $item->tanggal,
                    'uraian' => $item->uraian,
                    'saldo_keluar' => $item->saldo_keluar,
                ];
            });

            return response()->json([
                'data' => $formatted,
                'total_keluar' => $data->sum('saldo_keluar'),
                'sisa_akhir' => $sisa,
            ]);
        }


        public function store(Request $request)
        {
            $request->validate([
                'tanggal' => 'required|date',
                'uraian' => 'required|string',
                'saldo_keluar' => 'required|numeric|min:0',
            ]);

            Pengeluaran::create([
                'tanggal' => $request->tanggal,
                'uraian' => $request->uraian,
                'saldo_keluar' => $request->saldo_keluar,
            ]);

            return response()->json(['success' => true]);
        }

        public function update(Request $request, $id)
        {
            $request->validate([
                'tanggal' => 'required|date',
                'uraian' => 'required|string',
                'saldo_keluar' => 'required|numeric|min:0',
            ]);

            $data = Pengeluaran::find($id);

            if (!$data) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            }

            $data->update([
                'tanggal' => $request->tanggal,
                'uraian' => $request->uraian,
                'jenis' => $request->jenis,
                'saldo_keluar' => $request->saldo_keluar,
                // Jangan update sisa_saldo secara langsung!
            ]);

            return response()->json(['success' => true]);
        }


        public function destroy($id)
        {
            $deleted = Pengeluaran::where('id', $id)->delete();

            return response()->json([
                'success' => $deleted ? true : false,
                'message' => $deleted ? 'Data berhasil dihapus' : 'Data tidak ditemukan'
            ]);
        }



    }

?>