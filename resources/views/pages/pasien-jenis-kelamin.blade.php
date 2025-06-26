@extends('layouts.index')

@section('title', 'Distribusi Pasien Berdasarkan Jenis Kelamin')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Distribusi Pasien Berdasarkan Jenis Kelamin</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <!-- Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Pie Chart</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Donut Chart</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="donutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel -->
        <div class="card">
            <div class="card-header">
                <h4>Data Distribusi Pasien</h4>
                <div class="card-header-action">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#tambahDataModal">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Kelamin</th>
                                <th>Jumlah Pasien</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Wilayah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->jenis_kelamin }}</td>
                                    <td>{{ $item->jumlah_pasien }}</td>
                                    <td>{{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }}</td>
                                    <td>{{ $item->tahun }}</td>
                                    <td>{{ $item->wilayah ?? '-' }}</td>
                                    <td>
                                        <form action="{{ url('/pasien-jenis-kelamin/' . $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if($data->isEmpty())
                                <tr><td colspan="7" class="text-center">Belum ada data</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Modal Tambah Data -->

@endsection
<div class="modal fade" tabindex="-1" role="dialog" id="tambahDataModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('/pasien-jenis-kelamin') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Pasien per Jenis Kelamin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Pasien</label>
                        <input type="number" name="jumlah_pasien" class="form-control" placeholder="Masukkan jumlah pasien" required>
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
                                @foreach(range(2023, 2025) as $tahun)
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Wilayah (Opsional)</label>
                        <input type="text" name="wilayah" class="form-control" placeholder="Masukkan wilayah">
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctxPie = document.getElementById('pieChart').getContext('2d');
    var ctxDonut = document.getElementById('donutChart').getContext('2d');

    var chartData = {
        labels: {!! json_encode($chartData->pluck('jenis_kelamin')) !!},
        datasets: [{
            data: {!! json_encode($chartData->pluck('total')) !!},
            backgroundColor: ['#36A2EB', '#FF6384'],
        }]
    };

    var pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: chartData,
    });

    var donutChart = new Chart(ctxDonut, {
        type: 'doughnut',
        data: chartData,
    });
</script>
@endpush