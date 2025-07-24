@extends('app')

@section('content')
  <div class="row">
    <!-- Kolom utama -->
    <div class="col-md-8 mb-4">
      <!-- Slider / Carousel -->
      <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          @foreach($pengumuman as $data)
          <div class="carousel-item active">
            <img src="/banner.jpg" class="d-block w-100 rounded" alt="Banner Pengumuman">
            <div class="carousel-caption" style="opacity:75%;">
              <h5><a href="{{ route('pengumuman.show', $data->slug) }}" class="fw-bold" style="text-decoration: none;color:#fff;">
                {{ Str::limit($data->judul, 50) }}
              </a></h5>
              <p>{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }} | {{ $data->kategori }}</p>
            </div>
          </div>
          @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" style="background-color:rgb(33, 170, 124); border-radius:8px;"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" style="background-color:rgb(33, 170, 124); border-radius:8px;"></span>
        </button>
      </div>

    </div>

    <!-- Sidebar kanan -->
    <div class="col-md-4">
      <div class="sidebar">
        <input type="text" class="form-control mb-3" placeholder="Cari ...">

        <h5 class="text-white mb-3" style="text-align: center;">JADWAL SHOLAT</h5>
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

  <div class="row mt-3">
    <div class="artikel-wrapper">
      <!-- Tabs -->
      <ul class="nav nav-tabs" id="artikelTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active text-success" id="artikel-islam-tab" data-bs-toggle="tab" href="#artikel-islam" role="tab">ARTIKEL ISLAM</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-success" id="kegiatan-tab" data-bs-toggle="tab" href="#kegiatan" role="tab">KEGIATAN MASJID</a>
        </li>
      </ul>

      <!-- Tab content -->
      <div class="tab-content mt-3">
        <!-- Tab: Artikel Islam -->
        <div class="tab-pane fade show active" id="artikel-islam" role="tabpanel">
          <div class="row">
            <!-- Kolom Kiri - Artikel Utama -->
            <div class="col-md-7">
              @if($artikelIslam->count())
                @php $utama = $artikelIslam->first(); @endphp
                <div class="card border shadow-sm mb-3">
                  <img src="{{ asset('storage/' . $utama->gambar) }}" class="card-img-top" alt="{{ $utama->judul }}" style="max-height: 300px; object-fit: cover;">
                  <div class="card-body">
                    <h5 class="card-title text-success fw-bold">
                      <a href="{{ route('artikel.show', $utama->slug) }}" class="fw-bold text-success d-block" style="text-decoration: none;">
                        {{ Str::limit($utama->judul, 50) }}
                      </a>
                    </h5>
                    <small class="text-muted d-block mb-2">
                      oleh Admin | {{ \Carbon\Carbon::parse($utama->tanggal)->translatedFormat('d F Y') }} | {{ $utama->kategori }}
                    </small>
                    <p class="card-text">{{ Str::limit(strip_tags($utama->konten), 160, '...') }}</p>
                  </div>
                </div>
              @endif
            </div>

            <!-- Kolom Kanan - Daftar Artikel -->
            <div class="col-md-5">
              @foreach($artikelIslam->skip(1) as $artikel)
                <div class="d-flex border-bottom py-2">
                  <div style="width: 100px; height: 90px; overflow: hidden; border-radius: 6px;">
                    <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;">
                  </div>
                  <div class="ms-3">
                    <a href="{{ route('artikel.show', $artikel->slug) }}" class="fw-bold text-dark d-block" style="font-size: 0.9rem;text-decoration: none;">
                      {{ Str::limit($artikel->judul, 30) }}
                    </a>
                    <small class="text-muted">
                      oleh Admin | {{ \Carbon\Carbon::parse($artikel->tanggal)->translatedFormat('d F Y') }} | {{ $artikel->kategori }}
                    </small>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <!-- Tab: Kegiatan Masjid -->
        <div class="tab-pane fade" id="kegiatan" role="tabpanel">
          <div class="row">
            <!-- Kolom Kiri - Kegiatan Utama -->
            <div class="col-md-7">
              @if($kegiatanMasjid->count())
                @php $utamaK = $kegiatanMasjid->first(); @endphp
                <div class="card border shadow-sm mb-3">
                  <img src="{{ asset('storage/' . $utamaK->gambar) }}" class="card-img-top" alt="{{ $utamaK->judul }}" style="max-height: 300px; object-fit: cover;">
                  <div class="card-body">
                    <h5 class="card-title text-success fw-bold">
                      <a href="{{ route('kegiatan.show', $utamaK->slug) }}" class="fw-bold text-success d-block" style="text-decoration: none;">
                        {{ Str::limit($utamaK->kegiatan, 50) }}
                      </a>
                    </h5>
                    <small class="text-muted d-block mb-2">
                      oleh Admin | {{ \Carbon\Carbon::parse($utamaK->tanggal)->translatedFormat('d F Y') }} | Kegiatan Masjid
                    </small>
                    <p class="card-text">{{ Str::limit(strip_tags($utamaK->keterangan), 160, '...') }}</p>
                  </div>
                </div>
              @endif
            </div>

            <!-- Kolom Kanan - Daftar Kegiatan -->
            <div class="col-md-5">
              @foreach($kegiatanMasjid->skip(1) as $kegiatan)
                <div class="d-flex border-bottom py-2">
                  <div style="width: 100px; height: 90px; overflow: hidden; border-radius: 6px;">
                    <img src="{{ asset('storage/' . $kegiatan->gambar) }}" alt="{{ $kegiatan->kegiatan }}" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;">
                  </div>
                  <div class="ms-3">
                    <a href="{{ route('kegiatan.show', $kegiatan->slug) }}" class="fw-bold text-dark d-block" style="font-size: 0.9rem;text-decoration: none;">
                      {{ Str::limit($kegiatan->kegiatan, 30) }}
                    </a>
                    <small class="text-muted">
                      oleh Admin | {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }} | Kegiatan Masjid
                    </small>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
<!-- JS -->
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


</body>
</html>
