@extends('layouts.index')

@section('title', 'Dashboard Jenis Pembayaran')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Data Jenis Pembayaran per Bulan</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item">Statistik Pembayaran</div>
      <div class="breadcrumb-item">Data Jenis Pembayaran</div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card">
          <div class="card-header">
            <h4>Statistik Pembayaran per Jenis</h4>
            <div class="card-header-action">
              <button class="btn btn-primary" data-toggle="modal" data-target="#tambahDataModal">
                <i class="fas fa-plus"></i> Tambah Data
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped" id="table-1">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Pasien</th>
                    <th>Jenis Kelamin</th>
                    <th>Poli</th>
                    <th>Jenis Pembayaran</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data as $index => $item)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $item->nama_pasien }}</td>
                      <td>{{ $item->jenis_kelamin }}</td>
                      <td>{{ $item->poli }}</td>
                      <td>{{ $item->jenis_pembayaran }}</td>
                      <td>{{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }}</td>
                      <td>{{ $item->tahun }}</td>
                      <td>
                        <form action="{{ url('/pembayaran/' . $item->id) }}" method="POST" class="form-delete">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger btn-delete">Hapus</button>
                        </form>
                      </td>
                    </tr>
                  @endforeach

                  @if($data->isEmpty())
                    <tr>
                      <td colspan="8" class="text-center">Belum ada data</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

<!-- Modal Tambah Data -->
<div class="modal fade" tabindex="-1" role="dialog" id="tambahDataModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ url('/pembayaran') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Data Jenis Pembayaran</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label>Nama Pasien</label>
            <input type="text" name="nama_pasien" class="form-control" placeholder="Masukkan nama pasien" required>
          </div>

          <div class="form-group">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-control" required>
              <option value="">Pilih Jenis Kelamin</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>

          <div class="form-group">
            <label for="poli">Poli</label>
            <select name="poli" class="form-control" required>
              <option value="">-- Pilih Poli --</option>
              <option value="Poli Umum">Poli Umum</option>
              <option value="Poli Gigi">Poli Gigi</option>
              <option value="Poli Anak">Poli Anak</option>
              <option value="Poli Kandungan">Poli Kandungan</option>
              <option value="Poli Saraf">Poli Saraf</option>
              <option value="Poli Penyakit Dalam">Poli Penyakit Dalam</option>
            </select>
          </div>

          <div class="form-group">
            <label>Jenis Pembayaran</label>
            <select name="jenis_pembayaran" class="form-control" required>
              <option value="">Pilih Jenis Pembayaran</option>
              <option value="BPJS">BPJS</option>
              <option value="Umum">Umum</option>
              <option value="Asuransi Swasta">Asuransi Swasta</option>
              <option value="Perusahaan">Perusahaan</option>
            </select>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Bulan</label>
              <select name="bulan" class="form-control" required>
                <option value="">Pilih Bulan</option>
                @foreach(range(1, 12) as $bulan)
                  <option value="{{ $bulan }}">{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-6">
              <label>Tahun</label>
              <select name="tahun" class="form-control" required>
                <option value="">Pilih Tahun</option>
                @foreach(range(2023, 2026) as $tahun)
                  <option value="{{ $tahun }}">{{ $tahun }}</option>
                @endforeach
              </select>
            </div>
          </div>

        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        const form = this.closest('form');

        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: "Data yang dihapus tidak bisa dikembalikan!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#e74c3c',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  });
</script>
@endpush
