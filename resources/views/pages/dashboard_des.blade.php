@extends('layouts.index')

@section('title', 'Healthcare BI Dashboard')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Dashboard Kehadiran Dokter</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item">Statistik Kehadiran</div>
    </div>
  </div>

  <!-- Statistik Hari Ini -->
  <div class="row">
    @foreach ([['title' => 'Dokter Hadir', 'count' => $hadir ?? 0, 'icon' => 'fas fa-user-md', 'bg' => 'bg-primary'],
              ['title' => 'Cuti', 'count' => $cuti ?? 0, 'icon' => 'fas fa-calendar-minus', 'bg' => 'bg-warning'],
              ['title' => 'Tidak Hadir', 'count' => $tidakHadir ?? 0, 'icon' => 'fas fa-user-slash', 'bg' => 'bg-danger'] ] as $stat)
      <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-statistic-1">
          <div class="card-icon {{ $stat['bg'] }}">
            <i class="{{ $stat['icon'] }}"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header"><h4>{{ $stat['title'] }}</h4></div>
            <div class="card-body">{{ $stat['count'] }}</div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Grafik & Pie Chart -->
  <div class="row">
    <!-- Grafik Kehadiran -->
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h4>Grafik Kehadiran Dokter</h4>
            <small class="text-muted">Periode: {{ $periodeLabel ?? '' }}</small>
          </div>
          <form method="GET" action="{{ route('dashboard_des') }}">
            <div class="form-row align-items-center">
              <div class="col-auto">
                <select class="form-control form-control-sm" name="periode" onchange="this.form.submit()">
                  <option value="">-- Pilih Periode Cepat --</option>
                  <option value="today" {{ request('periode') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                  <option value="yesterday" {{ request('periode') == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                  <option value="7days" {{ request('periode') == '7days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                  <option value="30days" {{ request('periode') == '30days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                </select>
              </div>
            </div>
          </form>
        </div>
        <div class="card-body">
          <canvas id="chartKehadiran" height="350" style="min-width: 800px"></canvas>
        </div>
      </div>
    </div>

    <!-- Pie Chart Kehadiran -->
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">
          <h4>Distribusi Kehadiran</h4>
        </div>
        <div class="card-body">
          <canvas id="pieKehadiran" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('chartKehadiran').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: @json($labels),
        datasets: [
          {
            label: 'Hadir',
            data: @json($hadirPerHari),
            borderColor: '#36A2EB',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderWidth: 2,
            fill: true
          },
          {
            label: 'Cuti',
            data: @json($cutiPerHari),
            borderColor: '#FFCE56',
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            borderWidth: 2,
            fill: true
          },
          {
            label: 'Tidak Hadir',
            data: @json($tidakHadirPerHari),
            borderColor: '#FF6384',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderWidth: 2,
            fill: true
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              precision: 0
            }
          }
        }
      }
    });

    const pieCtx = document.getElementById('pieKehadiran').getContext('2d');
    new Chart(pieCtx, {
      type: 'pie',
      data: {
        labels: ['Hadir', 'Cuti', 'Tidak Hadir'],
        datasets: [{
          data: [{{ $hadir ?? 0 }}, {{ $cuti ?? 0 }}, {{ $tidakHadir ?? 0 }}],
          backgroundColor: ['#36A2EB', '#FFCE56', '#FF6384'],
          borderColor: ['#fff', '#fff', '#fff'],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom'
          },
          datalabels: {
            color: '#fff',
            formatter: (value, context) => {
              const data = context.chart.data.datasets[0].data;
              const total = data.reduce((a, b) => a + b, 0);
              return total ? ((value / total) * 100).toFixed(1) + '%' : '0%';
            },
            font: {
              weight: 'bold',
              size: 14
            }
          }
        }
      },
      plugins: [ChartDataLabels]
    });
  });
</script>
@endpush
