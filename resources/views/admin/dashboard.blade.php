@extends('admin.layouts.app')

@section('title', 'Super Admin')

@section('content')
<div class="col-12">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Super Admin</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Super Admin</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="col-xl-3 col-md-6">
    <div class="card stat-widget">
        <div class="card-body">
            <div class="stat-widget-header">
                <div class="stat-widget-icon bg-primary">
                    <i class="fas fa-pills"></i>
                </div>
                <div class="stat-widget-info">
                    <h6>Total Obat</h6>
                    <h4>{{ $totalObat ?? 0 }}</h4>
                </div>
            </div>
            <div class="stat-widget-footer">
                <a href="{{ route('admin.obat.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6">
    <div class="card stat-widget">
        <div class="card-body">
            <div class="stat-widget-header">
                <div class="stat-widget-icon bg-success">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-widget-info">
                    <h6>Penjualan Hari Ini</h6>
                    <h4>{{ $penjualanHariIni ?? 0 }}</h4>
                </div>
            </div>
            <div class="stat-widget-footer">
                <a href="{{ route('admin.penjualan.index') }}" class="btn btn-sm btn-success">Lihat Detail</a>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6">
    <div class="card stat-widget">
        <div class="card-body">
            <div class="stat-widget-header">
                <div class="stat-widget-icon bg-warning">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-widget-info">
                    <h6>Total Pelanggan</h6>
                    <h4>{{ $totalPelanggan ?? 0 }}</h4>
                </div>
            </div>
            <div class="stat-widget-footer">
                <a href="{{ route('admin.pelanggan') }}" class="btn btn-sm btn-warning">Lihat Semua</a>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6">
    <div class="card stat-widget">
        <div class="card-body">
            <div class="stat-widget-header">
                <div class="stat-widget-icon bg-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-widget-info">
                    <h6>Obat Kadaluarsa</h6>
                    <h4>{{ $obatKadaluarsa ?? 0 }}</h4>
                </div>
            </div>
            <div class="stat-widget-footer">
                <a href="{{ route('admin.list.obat.expired') }}" class="btn btn-sm btn-danger">Lihat Detail</a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <a href="{{ route('admin.obat.create') }}" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>Tambah Obat
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="{{ route('admin.pembelian.index') }}" class="btn btn-success w-100">
                        <i class="fas fa-truck me-2"></i>Pembelian
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="{{ route('admin.penjualan.index') }}" class="btn btn-info w-100">
                        <i class="fas fa-cash-register me-2"></i>Penjualan
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="{{ route('admin.laporan') }}" class="btn btn-warning w-100">
                        <i class="fas fa-chart-bar me-2"></i>Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="col-xl-8">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-history me-2"></i>Aktivitas Terbaru</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Aktivitas</th>
                            <th>User</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities ?? [] as $activity)
                        <tr>
                            <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->user->name ?? 'System' }}</td>
                            <td>
                                <span class="badge bg-{{ $activity->status === 'success' ? 'success' : 'warning' }}">
                                    {{ ucfirst($activity->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada aktivitas terbaru</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- System Status -->
<div class="col-xl-4">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-server me-2"></i>Status Sistem</h5>
        </div>
        <div class="card-body">
            <div class="system-status">
                <div class="status-item">
                    <span class="status-label">Database</span>
                    <span class="status-value text-success">
                        <i class="fas fa-check-circle"></i> Online
                    </span>
                </div>
                <div class="status-item">
                    <span class="status-label">Cache</span>
                    <span class="status-value text-success">
                        <i class="fas fa-check-circle"></i> Active
                    </span>
                </div>
                <div class="status-item">
                    <span class="status-label">Storage</span>
                    <span class="status-value text-success">
                        <i class="fas fa-check-circle"></i> Available
                    </span>
                </div>
                <div class="status-item">
                    <span class="status-label">Last Backup</span>
                    <span class="status-value text-info">
                        {{ now()->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-widget {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-widget:hover {
    transform: translateY(-5px);
}

.stat-widget-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.stat-widget-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
    font-size: 1.5rem;
}

.stat-widget-info h6 {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.stat-widget-info h4 {
    margin: 5px 0 0 0;
    font-weight: bold;
    color: #333;
}

.stat-widget-footer {
    border-top: 1px solid #eee;
    padding-top: 15px;
}

.system-status .status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.system-status .status-item:last-child {
    border-bottom: none;
}

.status-label {
    font-weight: 500;
    color: #333;
}

.status-value {
    font-size: 0.9rem;
}
</style>
@endsection 