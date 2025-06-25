@extends('layouts.index')

@section('title', 'Healthcare BI Dashboard')

@section('content')
<div class="section-header">
            <h1>Dashboard Unit</h1>
          </div>
          <div class="row justify-content-center g-4">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Jumlah Pasien</h4>
                  </div>
                  <div class="card-body">
                    {{ $jumlahPasien }}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="far fa-newspaper"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Jumlah Unit Tersedia</h4>
                  </div>
                  <div class="card-body">
                    6
                  </div>
                </div>
              </div>
            </div>                 
          </div>
          <div class="row">
            <div class="col-lg-8 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Statistics</h4>
                  <div class="card-header-action">
                    <div class="btn-group">
                      <div class="btn-group mb-3">
                        <a href="{{ url('/dashboard1?filter=bulan' . ($unit ? '&unit=' . urlencode($unit) : '')) }}"
                          class="btn {{ $filter == 'bulan' ? 'btn-primary' : 'btn-outline-primary' }}">
                          Bulan
                        </a>
                        <a href="{{ url('/dashboard1?filter=tahun' . ($unit ? '&unit=' . urlencode($unit) : '')) }}"
                          class="btn {{ $filter == 'tahun' ? 'btn-primary' : 'btn-outline-primary' }}">
                          Tahun
                        </a>
                      </div>

                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <canvas id="myChart" height="182"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-12 col-12 col-sm-12">
            <div class="card">
  <div class="card-header">
    <h4>Filter Unit / Poli</h4>
  </div>
  <div class="card-body">
    <div class="d-flex flex-column gap-2">

      {{-- Tombol Semua Unit --}}
      <a href="{{ url('/dashboard1?filter=' . $filter) }}"
         class="btn {{ is_null($unit) ? 'btn-primary' : 'btn-outline-primary' }} btn-lg btn-block mb-2">
         Semua Unit
      </a>

      {{-- Tombol Unit per Poli --}}
      @foreach($listUnit as $poli)
        <a href="{{ url('/dashboard1?filter=' . $filter . '&unit=' . urlencode($poli)) }}"
           class="btn {{ $unit === $poli ? 'btn-primary' : 'btn-outline-primary' }} btn-lg btn-block mb-2">
          {{ $poli }}
        </a>
      @endforeach

    </div>
  </div>
</div>

</div>

          </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('myChart').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: @json($labels),
      datasets: [{
        label: 'Jumlah Pasien',
        data: @json($dataValues),
        backgroundColor: 'rgba(99, 102, 241, 0.2)',
        borderColor: 'rgba(99, 102, 241, 1)',
        borderWidth: 2,
        tension: 0.4,
        pointRadius: 4,
        pointHoverRadius: 6,
        fill: true
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      },
      plugins: {
        legend: {
          display: true,
          position: 'top'
        }
      }
    }
  });
</script>

@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Jumlah Pasien',
            data: @json($dataValues),
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: { display: true, position: 'top' }
        }
    }
});
</script>
@endpush
