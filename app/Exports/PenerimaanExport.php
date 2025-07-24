<?php

namespace App\Exports;

use App\Models\Penerimaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;


class PenerimaanExport implements FromView, WithStyles
{
    protected $bulan, $tahun, $periode;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;

        $namaBulan = $bulan ? Carbon::create()->month($bulan)->translatedFormat('F') : null;
        $this->periode = $namaBulan ? "$namaBulan $tahun" : "Tahun $tahun";
    }

    public function view(): View
    {
        $query = Penerimaan::query();

        if ($this->bulan) {
            $query->whereMonth('tanggal', $this->bulan);
        }
        $query->whereYear('tanggal', $this->tahun);
        $data = $query->orderBy('tanggal', 'asc')->get();

        return view('admin.kas.penerimaan_excel', [
            'data' => $data,
            'periode' => $this->periode // ⬅ Kirim ke view
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = 2 + $sheet->getHighestRow();

        return [
            'A1:H1' => [
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],
                'font' => [
                    'bold' => true,
                ],
            ],
            'A' => ['alignment' => ['horizontal' => 'center']],
            'B' => ['alignment' => ['horizontal' => 'center']],
            'C' => ['alignment' => ['horizontal' => 'left']],
            'D:H' => [
                'alignment' => ['horizontal' => 'right'],
                'numberFormat' => [
                    'formatCode' => '#,##0',
                ],
            ],
        ];
    }
}

