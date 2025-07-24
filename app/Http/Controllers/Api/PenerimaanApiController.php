<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Models\Penerimaan;
    use Illuminate\Support\Facades\DB;

    class PenerimaanApiController extends Controller
    {
        
        public function index(Request $request)
        {
            $bulan = $request->get('bulan'); // format 01, 02, ..., 12
            $tahun = $request->get('tahun') ?? date('Y');

            $query = Penerimaan::query();

            if ($bulan) {
                $query->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun);
            }

            $data = $query->orderBy('tanggal', 'asc')->get();

            $sisa = 0;

            $formatted = $data->map(function ($item) use (&$sisa) {
                $sisa += $item->saldo_masuk;

                return [
                    'id' => $item->id,
                    'tanggal' => $item->tanggal,
                    'uraian' => $item->uraian,
                    'tunai' => $item->jenis === 'Tunai' ? $item->saldo_masuk : '-',
                    'tf_bca' => $item->jenis === 'TF BCA' ? $item->saldo_masuk : '-',
                    'tf_bri' => $item->jenis === 'TF BRI' ? $item->saldo_masuk : '-',
                    'saldo_masuk' => $item->saldo_masuk,
                    'sisa_saldo' => $sisa,
                ];
            });

            return response()->json([
                'data' => $formatted,
                'total_tunai' => $data->where('jenis', 'Tunai')->sum('saldo_masuk'),
                'total_bca' => $data->where('jenis', 'TF BCA')->sum('saldo_masuk'),
                'total_bri' => $data->where('jenis', 'TF BRI')->sum('saldo_masuk'),
                'total_masuk' => $data->sum('saldo_masuk'),
                'sisa_akhir' => $sisa,
            ]);
        }


        public function store(Request $request)
        {
            $request->validate([
                'tanggal' => 'required|date',
                'uraian' => 'required|string',
                'jenis' => 'required|in:Tunai,TF BCA,TF BRI',
                'saldo_masuk' => 'required|numeric|min:0',
            ]);

            Penerimaan::create([
                'tanggal' => $request->tanggal,
                'uraian' => $request->uraian,
                'jenis' => $request->jenis,
                'saldo_masuk' => $request->saldo_masuk,
            ]);

            return response()->json(['success' => true]);
        }

        public function update(Request $request, $id)
        {
            $request->validate([
                'tanggal' => 'required|date',
                'uraian' => 'required|string',
                'jenis' => 'required|in:Tunai,TF BCA,TF BRI',
                'saldo_masuk' => 'required|numeric|min:0',
            ]);

            $data = Penerimaan::find($id);

            if (!$data) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            }

            $data->update([
                'tanggal' => $request->tanggal,
                'uraian' => $request->uraian,
                'jenis' => $request->jenis,
                'saldo_masuk' => $request->saldo_masuk,
                // Jangan update sisa_saldo secara langsung!
            ]);

            return response()->json(['success' => true]);
        }


        public function destroy($id)
        {
            $deleted = Penerimaan::where('id', $id)->delete();

            return response()->json([
                'success' => $deleted ? true : false,
                'message' => $deleted ? 'Data berhasil dihapus' : 'Data tidak ditemukan'
            ]);
        }



    }

?>