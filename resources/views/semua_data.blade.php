@extends('app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h4>Kategori: {{ $judulHalaman }}</h4>
        @php
            $routeName = match($judulHalaman ?? '') {
                'Artikel Islam' => 'artikel.show', 'Pengumuman' => 'pengumuman.show',
                'Kegiatan Masjid' => 'kegiatan.show',
                default => '#',
            };
        @endphp

        @foreach($data as $item)
            <div class="card mb-4 p-3 shadow-sm rounded-3 border-0">
                <div class="d-flex">
                    {{-- Thumbnail jika ada --}}
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar" class="img-fluid rounded me-3" style="width: 200px; height: auto;">
                    @endif
                    <div>
                        <h5 class="fw-bold text-uppercase text-dark">{{ $item->judul ?? $item->kegiatan ?? 'Tanpa Judul' }}</h5>
                        <p class="text-muted mb-1">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</p>
                        <a href="{{ route($routeName, $item->slug) }}" class="btn btn-success rounded-pill px-4 py-2">Baca selengkapnya</a>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <div class="col-md-4">
        <div class="sidebar">
            <input type="text" class="form-control mb-3" placeholder="Cari ...">
            <h5 class="text-white mb-3 text-center">JADWAL SHOLAT</h5>
            <div class="jadwal-box" id="jadwal-sholat">Memuat...</div>
        </div>
        <div class="card mt-3">
          <div class="card-header bg-dark text-white text-center" style="border-radius:10px;">
              <h5>LAPORAN KEUANGAN</h5>
              <div class="jadwal-box">
              <h6><strong>Periode Bulan {{ $namaBulan }}</strong></h6>
              <table>
                  <tr>
                      <th>Saldo Awal</th>
                      <td>Rp. {{ number_format($saldoAwal, 0, ',', '.') }}</td>
                  </tr>
                  <tr>
                      <th>Penerimaan</th>
                      <td>Rp. {{ number_format($penerimaan, 0, ',', '.') }}</td>
                  </tr>
                  <tr>
                      <th>Pengeluaran</th>
                      <td>Rp. {{ number_format($pengeluaran, 0, ',', '.') }}</td>
                  </tr>
                  <tr>
                      <td></td>
                      <td></td>
                  </tr>
                  <tr>
                      <th>Kas Masjid</th>
                      <td>Rp. {{ number_format($kasMasjid, 0, ',', '.') }}</td>
                  </tr>
              </table>
              <a href="{{ route('components.detail_kas') }}" class="btn btn-sm btn-outline-success mt-4">Lihat Detail</a>
          </div>
          </div>
          
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