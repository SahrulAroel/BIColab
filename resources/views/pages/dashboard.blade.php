@extends('layouts.index')

@section('title', 'Healthcare BI Dashboard')

@section('content')
<div class="row">
  <!-- Patient Statistics Card -->
  <div class="col-12">
    <div class="card card-statistic-2">
      <div class="card-stats">
        <div class="card-stats-title">Patient Statistics -
          <div class="dropdown d-inline">
            <a class="font-weight-600 dropdown-toggle" data-toggle="dropdown" href="#" id="orders-month">August</a>
            <ul class="dropdown-menu dropdown-menu-sm">
              <li class="dropdown-title">Select Month</li>
              @foreach(['January','February','March','April','May','June','July','August'] as $month)
                <li><a href="#" class="dropdown-item{{ $month === 'August' ? ' active' : '' }}">{{ $month }}</a></li>
              @endforeach
            </ul>
          </div>
        </div>
        <div class="card-stats-items">
          <div class="card-stats-item">
            <div class="card-stats-item-count">142</div>
            <div class="card-stats-item-label">New Patients</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">327</div>
            <div class="card-stats-item-label">Outpatients</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">89</div>
            <div class="card-stats-item-label">Inpatients</div>
          </div>
        </div>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-user-injured"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Total Patients</h4>
        </div>
        <div class="card-body">
          558
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Bed Occupancy Card -->
  <div class="col-lg-6 col-md-6 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-chart">
        <canvas id="balance-chart" height="80"></canvas>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-procedures"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Bed Occupancy</h4>
        </div>
        <div class="card-body">
          78%
        </div>
      </div>
    </div>
  </div>

  <!-- Average Wait Time Card -->
  <div class="col-lg-6 col-md-6 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-chart">
        <canvas id="sales-chart" height="80"></canvas>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-clock"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Avg. Wait Time</h4>
        </div>
        <div class="card-body">
          24 min
        </div>
      </div>
    </div>
  </div>
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
        <h4>Patient Visits Trend</h4>
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
            <li class="media">
              <div class="media-body">
                <div class="float-right">
                  <div class="font-weight-600 text-muted text-small">{{ $cases }} Cases</div>
                </div>
                <div class="media-title">{{ $diagnosis }}</div>
                <div class="mt-1">
                  <div class="budget-price">
                    <div class="budget-price-square bg-primary" data-width="{{ $width }}%"></div>
                    <div class="budget-price-label">{{ $change }}</div>
                  </div>
                </div>
              </div>
            </li>
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