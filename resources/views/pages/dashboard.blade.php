@extends('layouts.index')

@section('title', 'Healthcare BI Dashboard')

@section('content')
<div class="row">
  <!-- Patient Statistics Card -->
  <div class="col-12">
    <div class="card card-statistic-2">
      <div class="card-stats">
        <div class="card-stats-title">Patient Statistics
          <div class="dropdown d-inline">
          </div>
        </div>
        <div class="card-stats-items">
  <div class="card-stats-item">
    <div class="card-stats-item-count">{{ $totalPasienBaru }}</div>
    <div class="card-stats-item-label">Pasien Baru</div>
  </div>
  <div class="card-stats-item">
    <div class="card-stats-item-count">{{ $totalPasienLama }}</div>
    <div class="card-stats-item-label">Pasien Lama</div>
  </div>
  <div class="card-stats-item">
    <div class="card-stats-item-count">{{ $totalPasien }}</div>
    <div class="card-stats-item-label">Total Pasien</div>
  </div>
</div>

      </div>
      <div class="card-icon">
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Bed Occupancy Card -->

</div>

<div class="row">
  <!-- Pasien Lama vs Pasien Baru (Full Width) -->
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>Pasien Lama vs Pasien Baru</h4>
      </div>
      <div class="card-body">
        <canvas id="myChart2sipa" height="120"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Patient Visits Trend Chart -->
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h4>Trend Statistik Penyakit Perbulan</h4>
      </div>
      <div class="card-body">
        <canvas id="myChart2" height="158"></canvas>
      </div>
    </div>
  </div>

  <!-- Top Diagnoses List -->
  <div class="col-lg-4">
    <div class="card gradient-bottom">
      <div class="card-header">
        <h4>Top Diagnoses</h4>
        <div class="card-header-action dropdown">
          <a href="#" data-toggle="dropdown" class="btn btn-danger dropdown-toggle">Month</a>
          <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
            <li class="dropdown-title">Select Period</li>
            <li><a href="#" class="dropdown-item">Today</a></li>
            <li><a href="#" class="dropdown-item">Week</a></li>
            <li><a href="#" class="dropdown-item active">Month</a></li>
            <li><a href="#" class="dropdown-item">This Year</a></li>
          </ul>
        </div>
      </div>
      <div class="card-body" id="top-5-scroll">
        <ul class="list-unstyled list-unstyled-border">
          @foreach([
            ['Hypertension', 86, '15% Increase', 64],
            ['Type 2 Diabetes', 67, '22% Increase', 84],
            ['Upper Resp. Infection', 63, '8% Decrease', 34],
            ['Arthritis', 28, '12% Increase', 45],
            ['Asthma', 19, '5% Increase', 35]
          ] as [$diagnosis, $cases, $change, $width])
            @foreach($topDiagnoses as $diagnosis)
  <li class="media">
    <div class="media-body">
      <div class="float-right">
        <div class="font-weight-600 text-muted text-small">{{ $diagnosis->total }} Cases</div>
      </div>
      <div class="media-title">{{ $diagnosis->jenis_penyakit }}</div>
      <div class="mt-1">
        <div class="budget-price">
          @php
            // Width untuk bar (dari total pasien, misal max 100 sebagai skala)
            $max = $topDiagnoses->max('total');
            $width = $max > 0 ? round(($diagnosis->total / $max) * 100) : 0;
          @endphp
          <div class="budget-price-square bg-primary" data-width="{{ $width }}%"></div>
          <div class="budget-price-label">{{ $width }}%</div>
        </div>
      </div>
    </div>
  </li>
@endforeach

          @endforeach
        </ul>
      </div>
      <div class="card-footer pt-3 d-flex justify-content-center">
        <div class="budget-price justify-content-center">
          <div class="budget-price-square bg-primary" data-width="20"></div>
          <div class="budget-price-label">Compared to last month</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  var ctx = document.getElementById("myChart2sipa").getContext('2d');

  var chartData = {
    labels: {!! json_encode($kunjungan->map(fn($item) => \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F'))) !!},
    datasets: [
      {
        label: 'Pasien Baru',
        data: {!! json_encode($kunjungan->pluck('total_baru')) !!},
        backgroundColor: 'rgba(63,82,227,0.8)',
        borderWidth: 0,
        tension: 0.4,
        fill: true
      },
      {
        label: 'Pasien Lama',
        data: {!! json_encode($kunjungan->pluck('total_lama')) !!},
        backgroundColor: 'rgba(254,86,83,0.7)',
        borderWidth: 0,
        tension: 0.4,
        fill: true
      }
    ]
  };

  var myChart2 = new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
      responsive: true,
      legend: { display: true },
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            return data.datasets[tooltipItem.datasetIndex].label + ': ' + tooltipItem.yLabel + ' pasien';
          }
        }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            stepSize: 50,
            callback: value => value + ' pasien'
          },
          gridLines: { color: '#f2f2f2' }
        }],
        xAxes: [{
          gridLines: { display: false }
        }]
      }
    }
  });
</script>

<script>
  var ctxTrend = document.getElementById("myChart2").getContext('2d');

var trendChart = new Chart(ctxTrend, {
  type: 'bar',
  data: {
    labels: {!! json_encode($bulanList->map(fn($b) => \Carbon\Carbon::create()->month($b)->translatedFormat('F'))) !!},
    datasets: [
      ...{!! json_encode($penyakitDatasets) !!},
      {
        label: 'Trend',
        data: {!! json_encode($trendData) !!}, // Pastikan variabel ini berisi data tren (array of numbers)
        type: 'line',
        borderColor: 'rgba(0, 200, 83, 1)',
        borderWidth: 2,
        fill: false,
        pointBackgroundColor: 'rgba(0, 200, 83, 1)',
        pointRadius: 4
      }
    ]
  },
  options: {
    responsive: true,
    legend: {
      position: 'bottom',
      display: true
    },
    tooltips: {
      mode: 'index',
      intersect: false,
      callbacks: {
        label: function(tooltipItem, data) {
          return data.datasets[tooltipItem.datasetIndex].label + ': ' + tooltipItem.yLabel + ' pasien';
        }
      }
    },
    scales: {
      yAxes: [{
        stacked: false,
        gridLines: {
          drawBorder: false,
          color: '#f2f2f2',
        },
        ticks: {
          beginAtZero: true,
          stepSize: 10, // Sesuaikan sesuai kebutuhan
          callback: function(value) {
            return value + ' pasien';
          }
        }
      }],
      xAxes: [{
        stacked: false,
        gridLines: {
          display: false,
          tickMarkLength: 15,
        }
      }]
    }
  }
});
</script>


@endpush

