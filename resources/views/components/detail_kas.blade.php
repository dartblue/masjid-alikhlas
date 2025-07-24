@extends('app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div style="border: 2px solid #28a745; padding: 20px; border-radius: 10px;">
            <h3>Detail Laporan Keuangan Masjid - Bulan {{ $bulanIndo }} {{ $tahun }}</h3>
            <h5>Saldo Awal {{ $bulanIndo }} {{ $tahun }} : Rp. {{ number_format($saldoAwal, 0, ',', '.') }}</h5>
            <hr>

            <h5 class="fw-bold">Penerimaan</h5>
            @php
                $groupedPenerimaan = collect($penerimaan)->groupBy(function($item) {
                    return \Carbon\Carbon::parse($item['tanggal'])->format('Y-m-d');
                });
            @endphp
            @forelse($groupedPenerimaan as $tgl => $items)
                <span class="float-start">- Donasi warga tgl {{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}</span>
                <span class="float-end">Rp. {{ number_format(collect($items)->sum('saldo_masuk'), 0, ',', '.') }}</span><br>
            @empty
                <span class="float-start">Tidak ada data penerimaan</span><br>
            @endforelse
            <span class="float-start fw-bold">Total Penerimaan</span>
            <span class="float-end fw-bold">Rp. {{ number_format($totalPenerimaan, 0, ',', '.') }}</span><br><br>

            <h5 class="fw-bold">Pengeluaran</h5>
            @forelse($pengeluaran as $item)
                <span class="float-start">- {{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d F Y') }} {{ $item['uraian'] }}</span>
                <span class="float-end">Rp. {{ number_format($item['saldo_keluar'], 0, ',', '.') }}</span><br>
            @empty
                <span class="float-start">Tidak ada data pengeluaran</span><br>
            @endforelse
            <span class="float-start fw-bold">Total Pengeluaran</span>
            <span class="float-end fw-bold">Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}</span><br>
            <hr>
            <h5>
                <span class="fw-bold float-start">Sisa Kas {{ $bulanIndo }} {{ $tahun }}</span>
                <span class="fw-bold float-end">Rp. {{ number_format($kasMasjid, 0, ',', '.') }}</span>
            </h5><br><br>
        </div>
    </div>

    <div class="col-md-4">
        <div class="sidebar">
            <input type="text" class="form-control mb-3" placeholder="Cari ...">
            <h5 class="text-white mb-3 text-center">JADWAL SHOLAT</h5>
            <div class="jadwal-box" id="jadwal-sholat">Memuat...</div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
async function loadJadwal() {
  try {
    const response = await fetch("/jadwal-sholat");
    const data = await response.json();
    const today = data.items[0];

    const tanggal = new Date(today.date_for);
    const bulanIndo = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli",
                       "Agustus", "September", "Oktober", "November", "Desember"];
    const tanggalFormat = `Hari ini ${tanggal.getDate()} ${bulanIndo[tanggal.getMonth()]} ${tanggal.getFullYear()}`;

    function convertTime(time12h) {
      const [time, modifier] = time12h.split(' ');
      let [hours, minutes] = time.split(':');

      if (modifier.toLowerCase() === 'pm' && hours !== '12') {
        hours = parseInt(hours, 10) + 12;
      }
      if (modifier.toLowerCase() === 'am' && hours === '12') {
        hours = '00';
      }
      return `${hours}:${minutes}`;
    }

    document.getElementById("jadwal-sholat").innerHTML = `
      <strong>${tanggalFormat}</strong>
      <table>
        <tr><th>Subuh</th><td>${convertTime(today.fajr)}</td></tr>
        <tr><th>Zuhur</th><td>${convertTime(today.dhuhr)}</td></tr>
        <tr><th>Ashar</th><td>${convertTime(today.asr)}</td></tr>
        <tr><th>Maghrib</th><td>${convertTime(today.maghrib)}</td></tr>
        <tr><th>Isya</th><td>${convertTime(today.isha)}</td></tr>
      </table>
    `;
  } catch (error) {
    document.getElementById("jadwal-sholat").innerHTML = "Gagal memuat jadwal sholat.";
  }
}
loadJadwal();
</script>
@endpush