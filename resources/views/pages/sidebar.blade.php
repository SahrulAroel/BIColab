<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('dashboard') }}">MediAnalytics</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ route('dashboard') }}">MA</a>
    </div>
    
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
      <li class="dropdown {{ request()->is('dashboard') ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-heartbeat"></i><span>Healthcare BI</span></a>
        <ul class="dropdown-menu">
          <li class="{{ request()->is('disease-dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('disease.dashboard') }}">Disease Dashboard</a>
          </li>
          <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">Operational Dashboard</a>
          </li>
          <li class="{{ request()->is('dashboard1') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard1') }}">Poli Dashboard By Eza</a>
          </li>
        </ul>
      </li>

      <li class="menu-header">Data</li>
      <li class="dropdown {{ request()->is('pbb') || request()->is('seasonal-trend') ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-procedures"></i> <span>Patient Care</span></a>
        <ul class="dropdown-menu">
          <li class="{{ request()->is('pbb') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pbb.index') }}">Data PPB</a>
          </li>
          <li class="{{ request()->is('seasonal-trend') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('seasonal.trend') }}">Tren Musiman</a>
          </li>
        </ul>
      </li>

      

      <li class="dropdown {{ request()->is('pembayaran.index') || request()->is('grafik-pembayaran') ? 'active' : '' }}">
  <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-invoice-dollar"></i> <span>Statistik Pembayaran</span></a>
  <ul class="dropdown-menu">
    <li class="{{ request()->is('pembayaran.index') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('pembayaran.index') }}">Data Pembayaran</a>
    </li>
    <li class="{{ request()->is('grafik-pembayaran') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('grafik.pembayaran') }}">Grafik Pembayaran</a>
    </li>
  </ul>
</li>



    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
      <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
        <i class="fas fa-question-circle"></i> Help Center
      </a>
    </div>
  </aside>
</div>
