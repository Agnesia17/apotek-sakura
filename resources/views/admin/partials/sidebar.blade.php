<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar custom-green-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header custom-green-header">
      <a href="{{ route('dashboard') }}" class="b-brand text-white text-decoration-none">
        <span class="fs-4 fw-bold">APOTEK SAKURA</span>
      </a>
    </div>
    <div class="navbar-content custom-green-content">
      <ul class="pc-navbar list-unstyled mb-0">
        <!-- Dashboard - Available for all admin roles -->
        @if(auth()->user()->canAccessMenu('dashboard'))
        <li class="pc-item mb-1 {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('apoteker.dashboard') ? 'active' : '' }}">
          <a href="{{ route('dashboard') }}" class="pc-link custom-menu-link {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('apoteker.dashboard') ? 'active' : '' }}">
            <span class="pc-micon me-3"><i class="ti ti-home fs-5"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>
        @endif

        <!-- Apoteker Section - Available for both superadmin and apoteker -->
        @if(auth()->user()->canAccessMenu('daftar_obat') || auth()->user()->canAccessMenu('transaksi'))
        <li class="pc-item pc-caption mt-4 mb-2">
          <label class="text-white-50 text-uppercase fw-bold small d-flex align-items-center">
            <i class="ti ti-user-cog me-2"></i>
            Apoteker
          </label>
        </li>
        @endif

        @if(auth()->user()->canAccessMenu('daftar_obat'))
        <li class="pc-item mb-1 {{ request()->routeIs('obat.*') || request()->routeIs('admin.obat.*') ? 'active' : '' }}">
          <a href="{{ route('obat.index') }}" class="pc-link custom-menu-link {{ request()->routeIs('obat.*') || request()->routeIs('admin.obat.*') ? 'active' : '' }}">
            <span class="pc-micon me-3"><i class="ti ti-pill fs-5"></i></span>
            <span class="pc-mtext">Obat</span>
          </a>
        </li>
        @endif

        @if(auth()->user()->canAccessMenu('transaksi'))
        <li class="pc-item mb-1 {{ request()->routeIs('penjualan.*') || request()->routeIs('admin.penjualan.*') ? 'active' : '' }}">
          <a href="{{ route('penjualan.index') }}" class="pc-link custom-menu-link {{ request()->routeIs('penjualan.*') || request()->routeIs('admin.penjualan.*') ? 'active' : '' }}">
            <span class="pc-micon me-3"><i class="ti ti-cash fs-5"></i></span>
            <span class="pc-mtext">Penjualan</span>
          </a>
        </li>
        @endif

        @if(auth()->user()->canAccessMenu('kadaluarsa'))
        <li class="pc-item mb-1 {{ request()->routeIs('list.obat.expired') || request()->routeIs('admin.list.obat.expired') ? 'active' : '' }}">
          <a href="{{ route('list.obat.expired') }}" class="pc-link custom-menu-link {{ request()->routeIs('list.obat.expired') || request()->routeIs('admin.list.obat.expired') ? 'active' : '' }}">
            <span class="pc-micon me-3"><i class="ti ti-alert-triangle fs-5"></i></span>
            <span class="pc-mtext">Expired</span>
          </a>
        </li>
        @endif

        <!-- Super Admin Section - Only available for superadmin -->
        @if(auth()->user()->isSuperAdmin())
        <li class="pc-item pc-caption mt-4 mb-2">
          <label class="text-white-50 text-uppercase fw-bold small d-flex align-items-center">
            <i class="ti ti-shield-lock me-2"></i>
            Super Admin
          </label>
        </li>
        
        <li class="pc-item mb-1 {{ request()->routeIs('admin.apoteker.*') ? 'active' : '' }}">
          <a href="{{ route('admin.apoteker.index') }}" class="pc-link custom-menu-link {{ request()->routeIs('admin.apoteker.*') ? 'active' : '' }}">
            <span class="pc-micon me-3"><i class="ti ti-users fs-5"></i></span>
            <span class="pc-mtext">Daftar Apoteker</span>
          </a>
        </li>
        
        <li class="pc-item mb-1 {{ request()->routeIs('admin.supplier*') ? 'active' : '' }}">
          <a href="{{ route('admin.supplier') }}" class="pc-link custom-menu-link {{ request()->routeIs('admin.supplier*') ? 'active' : '' }}">
            <span class="pc-micon me-3"><i class="ti ti-truck-delivery fs-5"></i></span>
            <span class="pc-mtext">Daftar Supplier</span>
          </a>
        </li>
        
        <li class="pc-item mb-1 {{ request()->routeIs('pembelian*') || request()->routeIs('admin.pembelian*') ? 'active' : '' }}">
          <a href="{{ route('pembelian.index') }}" class="pc-link custom-menu-link {{ request()->routeIs('pembelian*') || request()->routeIs('admin.pembelian*') ? 'active' : '' }}">
            <span class="pc-micon me-3"><i class="ti ti-shopping-cart fs-5"></i></span>
            <span class="pc-mtext">Pembelian</span>
          </a>
        </li>
        
        <li class="pc-item mb-1 {{ request()->routeIs('admin.pelanggan*') ? 'active' : '' }}">
          <a href="{{ route('admin.pelanggan') }}" class="pc-link custom-menu-link {{ request()->routeIs('admin.pelanggan*') ? 'active' : '' }}">
            <span class="pc-micon me-3"><i class="ti ti-user fs-5"></i></span>
            <span class="pc-mtext">Daftar Pelanggan</span>
          </a>
        </li>
        
        <li class="pc-item mb-1 {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
          <a href="{{ route('admin.laporan') }}" class="pc-link custom-menu-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
            <span class="pc-micon me-3"><i class="ti ti-report fs-5"></i></span>
            <span class="pc-mtext">Laporan</span>
          </a>
        </li>
        @endif

        <!-- Others Section - Available for all -->
        <li class="pc-item pc-caption mt-4 mb-2">
          <label class="text-white-50 text-uppercase fw-bold small d-flex align-items-center">
            <i class="ti ti-dots-circle-horizontal me-2"></i>
            Lainnya
          </label>
        </li>
        
        <li class="pc-item mb-1">
          <form method="POST" action="{{ route('admin.logout') }}" class="d-inline w-100">
            @csrf
            <button type="submit" class="pc-link custom-logout-btn">
              <span class="pc-micon me-3"><i class="ti ti-logout fs-5"></i></span>
              <span class="pc-mtext">Logout</span>
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Custom CSS untuk sidebar hijau #198754 -->
<style>
/* Sidebar Background */
.custom-green-sidebar {
    background-color: #198754 !important;
    min-height: 100vh;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
}

.custom-green-header {
    background-color: #198754 !important;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    padding: 1rem;
}

.custom-green-content {
    background-color: #198754 !important;
    padding: 1rem;
}

/* Menu Links */
.custom-menu-link {
    color: #ffffff !important;
    text-decoration: none !important;
    display: flex !important;
    align-items: center !important;
    padding: 12px 16px !important;
    border-radius: 8px !important;
    margin-bottom: 4px !important;
    transition: all 0.3s ease !important;
    border: none !important;
    background: transparent !important;
    width: 100% !important;
    text-align: left !important;
}

/* Hover Effect */
.custom-menu-link:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
    transform: translateX(4px);
}

/* Active State untuk Button - Menghilangkan warna biru */
.custom-menu-link.active {
    background-color: #ffffff !important;
    color: #198754 !important;
    font-weight: 600 !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
    border-left: 4px solid #ffffff !important;
}

.custom-menu-link.active:hover {
    background-color: #f8f9fa !important;
    color: #198754 !important;
    transform: translateX(0px);
}

/* Logout Button */
.custom-logout-btn {
    color: #ffffff !important;
    text-decoration: none !important;
    display: flex !important;
    align-items: center !important;
    padding: 12px 16px !important;
    border-radius: 8px !important;
    margin-bottom: 4px !important;
    transition: all 0.3s ease !important;
    border: none !important;
    background: transparent !important;
    width: 100% !important;
    text-align: left !important;
    cursor: pointer !important;
}

.custom-logout-btn:hover {
    background-color: #dc3545 !important;
    color: #ffffff !important;
    transform: translateX(4px);
}

/* Caption Labels */
.pc-caption label {
    color: rgba(255, 255, 255, 0.7) !important;
    text-transform: uppercase !important;
    font-weight: 600 !important;
    font-size: 0.75rem !important;
    letter-spacing: 0.5px !important;
    margin-bottom: 8px !important;
    display: flex !important;
    align-items: center !important;
}

.pc-caption i {
    font-size: 0.875rem !important;
    margin-right: 6px !important;
}

/* Brand Link */
.b-brand {
    transition: all 0.3s ease;
}

.b-brand:hover {
    color: rgba(255, 255, 255, 0.8) !important;
}

/* Icon Styling */
.pc-micon i {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
    .custom-green-sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .custom-green-sidebar.show {
        transform: translateX(0);
    }
}
</style>
<!-- [ Sidebar Menu ] end -->