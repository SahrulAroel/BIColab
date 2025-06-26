@extends('layouts.index')   

@section('title', 'Grafik Pembayaran')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Grafik Jenis Pembayaran</h1>
  </div>

  <div class="section-body">
    <!-- Card Rekap -->
    <div class="row">
      @foreach (['BPJS' => 'primary', 'Umum' => 'success', 'Asuransi Swasta' => 'warning', 'Perusahaan' => 'danger'] as $label => $color)
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card card-statistic-1">
            <div class="card-icon bg-{{ $color }}">
              <i class="fas fa-wallet"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header"><h4>{{ $label }}</h4></div>
              <div class="card-body">{{ $summary[$label] ?? 0 }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Grafik Batang -->
    <div class="card mt-4">
      <div class="card-header">
        <h4>Diagram Batang Pembayaran</h4>
      </div>
      <div class="card-body">
        <canvas id="grafikPembayaran" height="100"></canvas>
      </div>
    </div>

    <!-- Pie Chart -->
    <div class="card mt-4">
      <div class="card-header">
        <h4>Distribusi Persentase Pembayaran</h4>
      </div>
      <div class="card-body d-flex justify-content-center">
        <div style="width: 400px; height: 400px;">
          <canvas id="piePembayaran" width="400" height="400"></canvas>
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
  const rawData = {!! json_encode($data) !!};

  const namaBulan = [
    '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ];

  const labelSet = new Set(rawData.map(d => `${d.bulan}-${d.tahun}`));
  const sortedLabels = Array.from(labelSet).sort((a, b) => {
    const [bulanA, tahunA] = a.split('-').map(Number);
    const [bulanB, tahunB] = b.split('-').map(Number);
    return (tahunA * 100 + bulanA) - (tahunB * 100 + bulanB);
  });

  const labels = sortedLabels.map(item => {
    const [bulan, tahun] = item.split('-');
    return `${namaBulan[parseInt(bulan)]} ${tahun}`;
  });

  const jenisList = [...new Set(rawData.map(d => d.jenis_pembayaran))];

  // 🌈 Warna transparan RGBA
  const colors = {
    'BPJS': 'rgba(103, 119, 239, 0.6)',         // biru
    'Umum': 'rgba(71, 195, 99, 0.6)',           // hijau
    'Asuransi Swasta': 'rgba(255, 164, 38, 0.6)', // oranye
    'Perusahaan': 'rgba(252, 84, 75, 0.6)',     // merah
  };

  const datasets = jenisList.map(jenis => ({
    label: jenis,
    backgroundColor: colors[jenis] || 'rgba(128,128,128,0.4)',
    hoverBackgroundColor: (colors[jenis] || 'rgba(128,128,128,0.4)').replace('0.6', '0.9'),
    // ⚠️ no borderRadius or borderSkipped, biar tetap kotak
    data: sortedLabels.map(label => {
      const [bulan, tahun] = label.split('-');
      const found = rawData.find(d => d.jenis_pembayaran === jenis && d.bulan == bulan && d.tahun == tahun);
      return found ? found.total : 0;
    })
  }));

  const ctx = document.getElementById('grafikPembayaran').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: { labels, datasets },
    options: {
      responsive: true,
      animation: {
        duration: 1000,
        easing: 'easeOutQuart'
      },
      plugins: {
        legend: { position: 'top' },
        tooltip: { mode: 'index', intersect: false }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { precision: 0 }
        }
      }
    }
  });

  const pieCtx = document.getElementById('piePembayaran').getContext('2d');
  new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels: @json($pieLabels),
      datasets: [{
        data: @json($pieData),
        backgroundColor: [
          colors['BPJS'],
          colors['Umum'],
          colors['Asuransi Swasta'],
          colors['Perusahaan']
        ],
        borderColor: '#ffffff',
        borderWidth: 3,
        hoverOffset: 15
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { boxWidth: 20, padding: 20 }
        },
        datalabels: {
          color: '#fff',
          formatter: (value, context) => {
            const data = context.chart.data.datasets[0].data;
            const total = data.reduce((a, b) => a + b, 0);
            return total ? ((value / total) * 100).toFixed(1) + '%' : '0%';
          },
          font: { weight: 'bold', size: 14 }
        }
      }
    },
    plugins: [ChartDataLabels]
  });
});
</script>
@endpush
