@extends('layouts.index')

@section('title', 'Healthcare BI Dashboard')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Jadwal Dokter</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item">Layanan</div>
      <div class="breadcrumb-item">Jadwal Dokter</div>
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
            <h4>Data Jadwal Dokter</h4>
            <div class="card-header-action">
              <button class="btn btn-primary" data-toggle="modal" data-target="#tambahDokterModal">
                <i class="fas fa-plus"></i> Tambah Jadwal
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped" id="table-1">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Dokter</th>
                    <th>Spesialis</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data as $index => $item)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $item->nama_dokter }}</td>
                      <td>{{ $item->spesialis }}</td>
                      <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                      <td>
                        @if($item->status == 'Hadir')
                          <span class="badge badge-success">Hadir</span>
                        @elseif($item->status == 'Cuti')
                          <span class="badge badge-warning">Cuti</span>
                        @else
                          <span class="badge badge-danger">Tidak Hadir</span>
                        @endif
                      </td>
                      <td>
                        <form action="{{ url('/jadwal/' . $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                  @if($data->isEmpty())
                    <tr><td colspan="6" class="text-center">Belum ada data</td></tr>
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

<!-- Modal Tambah Jadwal Dokter -->
<div class="modal fade" tabindex="-1" role="dialog" id="tambahDokterModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ url('/jadwal') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Jadwal Dokter</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Dokter</label>
            <input type="text" name="nama_dokter" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Spesialis</label>
            <select name="spesialis" class="form-control" required>
              <option value="">Pilih Spesialisasi</option>
              <option value="Umum">Umum</option>
              <option value="Anak">Anak</option>
              <option value="Gigi">Gigi</option>
              <option value="Kandungan">Kandungan</option>
              <option value="Penyakit Dalam">Penyakit Dalam</option>
              <option value="Paru">Paru</option>
            </select>
          </div>
          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Status Kehadiran</label>
            <select name="status" class="form-control" required>
              <option value="">Pilih Status</option>
              <option value="Hadir">Hadir</option>
              <option value="Cuti">Cuti</option>
              <option value="Tidak Hadir">Tidak Hadir</option>
            </select>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
        </div>
      </form>
    </div>
  </div>
</div>
