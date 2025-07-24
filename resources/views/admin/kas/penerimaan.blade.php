@extends('layouts.admin')

@section('title', 'Penerimaan')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3 flex-wrap">
    <div>
        <h4 class="mb-1">Masjid Keandra Park Al-Ikhlas</h4>
        <h5 class="text-muted">Data Penerimaan Donasi</h5>
    </div>
    <div class="d-flex flex-column align-items-start gap-2">
        <div class="d-flex gap-2 flex-wrap">
            <button type="button"  class="btn btn-primary" onclick="showModal()">+ Tambah Data</button>
            <button class="btn btn-outline-danger" onclick="exportPdf()">Export PDF</button>
            <button onclick="exportExcel()" class="btn btn-outline-success">Export Excel</button>
        </div>

        <div class="d-flex align-items-center gap-2 mt-1">
            <label class="mb-0 fw-semibold">Periode</label>
            <select id="bulanFilter" class="form-select" style="width: 180px">
                <option value="">Pilih Bulan</option>
                @php
                    \Carbon\Carbon::setLocale('id');
                @endphp
                @foreach(range(1, 12) as $b)
                    <option value="{{ sprintf('%02d', $b) }}">{{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}</option>
                @endforeach
            </select>
            <select id="tahunFilter" class="form-select" style="width: 120px">
                @for($y = 2024; $y <= now()->year; $y++)
                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>

</div>


<div class="table-responsive">
    <table class="table table-bordered text-center align-middle">
        <thead class="table-light text-center align-middle">
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Tanggal</th>
                <th rowspan="2">Uraian</th>
                <th colspan="3">Jenis Penerimaan</th>
                <th rowspan="2">Saldo Masuk</th>
                <th rowspan="2">Sisa Saldo</th>
                <th rowspan="2">Action</th>
            </tr>
            <tr>
                <th>Tunai</th>
                <th>TF BCA</th>
                <th>TF BRI</th>
            </tr>
        </thead>
        <tbody id="penerimaanBody">
            <tr><td colspan="8">Memuat data...</td></tr>
        </tbody>
        <tfoot class="table-light fw-bold">
            <tr>
                <td colspan="3" class="text-center">Jumlah Penerimaan Donasi</td>
                <td id="totalTunai">-</td>
                <td id="totalBCA">-</td>
                <td id="totalBRI">-</td>
                <td id="totalMasuk">-</td>
                <td id="sisaAkhir">-</td>
                <td id="action">-</td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Modal Tambah Data (opsional, next step) -->
<div class="modal fade" id="modalTambahData" tabindex="-1" aria-labelledby="modalTambahDataLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formTambahData">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahDataLabel">Tambah Penerimaan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          @csrf
          <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" name="tanggal" required>
          </div>
          <div class="mb-3">
            <label for="uraian" class="form-label">Uraian</label>
            <input type="text" class="form-control" name="uraian" required>
          </div>
          <div class="mb-3">
            <label for="jenis" class="form-label">Jenis Pembayaran</label>
            <select name="jenis" class="form-select" required>
              <option value="">-- Pilih Jenis --</option>
              <option value="Tunai">Tunai</option>
              <option value="TF BCA">TF BCA</option>
              <option value="TF BRI">TF BRI</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="saldo_masuk" class="form-label">Jumlah</label>
            <input type="number" class="form-control" name="saldo_masuk" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
    let jumlahDataPenerimaan = 0;

    const bulanFilter = document.getElementById('bulanFilter');
    const tahunFilter = document.getElementById('tahunFilter');

    function loadData() {
        const bulan = bulanFilter.value;
        const tahun = tahunFilter.value;
        const url = `/api/penerimaan?bulan=${bulan}&tahun=${tahun}`;

        fetch(url)
            .then(res => res.json())
            .then(res => {
                jumlahDataPenerimaan = res.data.length || 0;
                const tbody = document.getElementById('penerimaanBody');
                tbody.innerHTML = '';

                if (!res.data || res.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="8">Data tidak ditemukan.</td></tr>';
                    updateFooter('-', '-', '-', '-', '-', '-');
                    return;
                }

                res.data.forEach((item, i) => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${formatTanggal(item.tanggal)}</td>
                            <td align='left'>${item.uraian}</td>
                            <td>${item.tunai !== '-' ? formatRupiah(item.tunai) : '-'}</td>
                            <td>${item.tf_bca !== '-' ? formatRupiah(item.tf_bca) : '-'}</td>
                            <td>${item.tf_bri !== '-' ? formatRupiah(item.tf_bri) : '-'}</td>
                            <td>${formatRupiah(item.saldo_masuk)}</td>
                            <td>${formatRupiah(item.sisa_saldo)}</td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1" onclick="editData(${item.id})"><i class="bx bx-edit"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="hapusData(${item.id})"><i class="bx bx-trash"></i></button>
                            </td>
                        </tr>`;
                });

                updateFooter(
                    formatRupiah(res.total_tunai),
                    formatRupiah(res.total_bca),
                    formatRupiah(res.total_bri),
                    formatRupiah(res.total_masuk),
                    formatRupiah(res.sisa_akhir)
                );
            })
            .catch(err => {
                console.error('Gagal memuat data', err);
            });
    }

    function updateFooter(tunai, bca, bri, masuk, akhir) {
        document.getElementById('totalTunai').textContent = tunai;
        document.getElementById('totalBCA').textContent = bca;
        document.getElementById('totalBRI').textContent = bri;
        document.getElementById('totalMasuk').textContent = masuk;
        document.getElementById('sisaAkhir').textContent = akhir;
        document.getElementById('action').textContent = '-';
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function formatTanggal(tanggalString) {
        const date = new Date(tanggalString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    }


    // Load saat halaman dibuka dan saat dropdown berubah
    document.addEventListener('DOMContentLoaded', loadData);
    bulanFilter.addEventListener('change', loadData);
    tahunFilter.addEventListener('change', loadData);


    function showModal() {
        const form = document.getElementById('formTambahData');
        form.reset(); // reset isian form
        delete form.dataset.editingId; // pastikan ini mode "tambah"
        document.querySelector('#modalTambahDataLabel').textContent = 'Tambah Penerimaan';

        const modal = new bootstrap.Modal(document.getElementById('modalTambahData'));
        modal.show();
    }


    document.getElementById('formTambahData').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const isEdit = form.dataset.editingId;
        const data = {
            tanggal: form.tanggal.value,
            uraian: form.uraian.value,
            jenis: form.jenis.value,
            saldo_masuk: form.saldo_masuk.value
        };

        const url = isEdit ? `/api/penerimaan/${isEdit}` : '/api/penerimaan';
        const method = isEdit ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                alert(isEdit ? 'Data berhasil diedit' : 'Data berhasil ditambahkan');
                bootstrap.Modal.getInstance(document.getElementById('modalTambahData')).hide();
                form.reset();
                delete form.dataset.editingId;
                document.querySelector('#modalTambahDataLabel').textContent = 'Tambah Penerimaan';
                loadData();
            } else {
                alert('Gagal menyimpan data');
            }
        })
        .catch(err => {
            console.error('Gagal simpan data:', err);
            alert('Terjadi kesalahan saat menyimpan');
        });
    });

</script>
<script>
    function editData(id) {
        fetch(`/api/penerimaan?bulan=${bulanFilter.value}&tahun=${tahunFilter.value}`)
            .then(res => res.json())
            .then(res => {
                const item = res.data.find(d => d.id == id);
                if (!item) return alert("Data tidak ditemukan");

                // Isi form dengan data yang akan diedit
                const form = document.getElementById('formTambahData');
                form.dataset.editingId = id;
                form.tanggal.value = item.tanggal;
                form.uraian.value = item.uraian;
                form.jenis.value = item.tunai !== '-' ? 'Tunai' :
                                    item.tf_bca !== '-' ? 'TF BCA' : 'TF BRI';
                form.saldo_masuk.value = item.saldo_masuk;

                document.querySelector('#modalTambahDataLabel').textContent = 'Edit Data Penerimaan';
                const modal = new bootstrap.Modal(document.getElementById('modalTambahData'));
                modal.show();
            });
    }


    function hapusData(id) {
        if (!confirm('Yakin ingin menghapus data ini?')) return;

        fetch(`/api/penerimaan/${id}`, {
            method: 'DELETE'
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                alert('Data berhasil dihapus');
                loadData();
            } else {
                alert('Gagal menghapus data');
            }
        })
        .catch(err => {
            console.error('Gagal hapus:', err);
            alert('Terjadi kesalahan saat menghapus');
        });
    }

</script>
<script>
    function exportPdf() {
        if (jumlahDataPenerimaan === 0) {
            alert('Tidak ada data yang dapat diexport.');
            return;
        }

        const bulan = document.getElementById('bulanFilter').value;
        const tahun = document.getElementById('tahunFilter').value;
        const url = `/export/penerimaan/pdf?bulan=${bulan}&tahun=${tahun}`;
        window.location.href = url;
    }

</script>
<script>
function exportExcel() {
    if (jumlahDataPenerimaan === 0) {
        alert('Tidak ada data yang dapat diexport.');
        return;
    }
    const bulan = document.getElementById('bulanFilter').value;
    const tahun = document.getElementById('tahunFilter').value;
    const url = `/export/penerimaan/excel?bulan=${bulan}&tahun=${tahun}`;
    window.location.href = url;
}
</script>

@endpush
