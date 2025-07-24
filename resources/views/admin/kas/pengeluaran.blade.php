@extends('layouts.admin')

@section('title', 'Pengeluaran')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3 flex-wrap">
    <div>
        <h4 class="mb-1">Masjid Keandra Park Al-Ikhlas</h4>
        <h5 class="text-muted">Data Pengeluaran Kas Masjid</h5>
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
                <th>No</th>
                <th>Tanggal</th>
                <th>Uraian</th>
                <th>Saldo Keluar</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="pengeluaranBody">
            <tr><td colspan="8">Memuat data...</td></tr>
        </tbody>
        <tfoot class="table-light fw-bold">
            <tr>
                <td colspan="3" class="text-center">Jumlah Pengeluaran</td>
                <td id="totalKeluar">-</td>
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
          <h5 class="modal-title" id="modalTambahDataLabel">Tambah Pengeluaran</h5>
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
            <label for="saldo_keluar" class="form-label">Jumlah</label>
            <input type="number" class="form-control" name="saldo_keluar" required>
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
    let jumlahDataPengeluaran = 0;

    const bulanFilter = document.getElementById('bulanFilter');
    const tahunFilter = document.getElementById('tahunFilter');

    function loadData() {
        const bulan = bulanFilter.value;
        const tahun = tahunFilter.value;
        const url = `/api/pengeluaran?bulan=${bulan}&tahun=${tahun}`;

        fetch(url)
            .then(res => res.json())
            .then(res => {
                jumlahDataPengeluaran = res.data.length || 0;
                const tbody = document.getElementById('pengeluaranBody');
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
                            <td>${formatRupiah(item.saldo_keluar)}</td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1" onclick="editData(${item.id})"><i class="bx bx-edit"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="hapusData(${item.id})"><i class="bx bx-trash"></i></button>
                            </td>
                        </tr>`;
                });

                updateFooter(
                    formatRupiah(res.total_keluar),
                    formatRupiah(res.sisa_akhir)
                );
            })
            .catch(err => {
                console.error('Gagal memuat data', err);
            });
    }

    function updateFooter(keluar, akhir) {
        document.getElementById('totalKeluar').textContent = keluar;
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
        document.querySelector('#modalTambahDataLabel').textContent = 'Tambah Pengeluaran';

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
            saldo_keluar: form.saldo_keluar.value
        };

        const url = isEdit ? `/api/pengeluaran/${isEdit}` : '/api/pengeluaran';
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
                document.querySelector('#modalTambahDataLabel').textContent = 'Tambah Pengeluaran';
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
        fetch(`/api/pengeluaran?bulan=${bulanFilter.value}&tahun=${tahunFilter.value}`)
            .then(res => res.json())
            .then(res => {
                const item = res.data.find(d => d.id == id);
                if (!item) return alert("Data tidak ditemukan");

                // Isi form dengan data yang akan diedit
                const form = document.getElementById('formTambahData');
                form.dataset.editingId = id;
                form.tanggal.value = item.tanggal;
                form.uraian.value = item.uraian;
                form.saldo_keluar.value = item.saldo_keluar;

                document.querySelector('#modalTambahDataLabel').textContent = 'Edit Data Pengeluaran';
                const modal = new bootstrap.Modal(document.getElementById('modalTambahData'));
                modal.show();
            });
    }


    function hapusData(id) {
        if (!confirm('Yakin ingin menghapus data ini?')) return;

        fetch(`/api/pengeluaran/${id}`, {
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
        if (jumlahDataPengeluaran === 0) {
            alert('Tidak ada data yang dapat diexport.');
            return;
        }

        const bulan = document.getElementById('bulanFilter').value;
        const tahun = document.getElementById('tahunFilter').value;
        const url = `/export/pengeluaran/pdf?bulan=${bulan}&tahun=${tahun}`;
        window.location.href = url;
    }

</script>
<script>
function exportExcel() {
    if (jumlahDataPengeluaran === 0) {
        alert('Tidak ada data yang dapat diexport.');
        return;
    }
    const bulan = document.getElementById('bulanFilter').value;
    const tahun = document.getElementById('tahunFilter').value;
    const url = `/export/pengeluaran/excel?bulan=${bulan}&tahun=${tahun}`;
    window.location.href = url;
}
</script>

@endpush
