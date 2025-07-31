@extends('admin.layouts.app')

@section('title', 'Dashboard Apoteker - Apotek Sakura')

@section('content')
<div class="col-12">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Dashboard Apoteker</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('apoteker.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Apoteker</li>
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
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-widget-info">
                    <h6>Stok Menipis</h6>
                    <h4>{{ $stokMenipis ?? 0 }}</h4>
                </div>
            </div>
            <div class="stat-widget-footer">
                <a href="{{ route('admin.obat.index') }}?filter=low_stock" class="btn btn-sm btn-warning">Lihat Detail</a>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6">
    <div class="card stat-widget">
        <div class="card-body">
            <div class="stat-widget-header">
                <div class="stat-widget-icon bg-danger">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-widget-info">
                    <h6>Akan Kadaluarsa</h6>
                    <h4>{{ $akanKadaluarsa ?? 0 }}</h4>
                </div>
            </div>
            <div class="stat-widget-footer">
                <a href="{{ route('admin.list.obat.expired') }}" class="btn btn-sm btn-danger">Lihat Detail</a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions for Apoteker -->
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <a href="{{ route('admin.obat.index') }}" class="btn btn-primary w-100">
                        <i class="fas fa-pills me-2"></i>Kelola Obat
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('admin.penjualan.index') }}" class="btn btn-success w-100">
                        <i class="fas fa-cash-register me-2"></i>Transaksi
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('admin.pelanggan') }}" class="btn btn-info w-100">
                        <i class="fas fa-users me-2"></i>Data Pelanggan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="col-xl-8">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-history me-2"></i>Transaksi Terbaru</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions ?? [] as $transaction)
                        <tr>
                            <td>
                                @if($transaction->id)
                                    <a href="{{ route('admin.penjualan.show', $transaction->id) }}" class="text-primary">
                                        {{ $transaction->no_invoice ?? 'N/A' }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $transaction->pelanggan->nama ?? 'N/A' }}</td>
                            <td>Rp {{ number_format($transaction->total_harga ?? 0, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $transaction->status === 'selesai' ? 'success' : ($transaction->status === 'diproses' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($transaction->status ?? 'unknown') }}
                                </span>
                            </td>
                            <td>{{ $transaction->tanggal ? $transaction->tanggal->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada transaksi terbaru</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Stock Alerts -->
<div class="col-xl-4">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-exclamation-triangle me-2"></i>Peringatan Stok</h5>
        </div>
        <div class="card-body">
            <div class="stock-alerts">
                @forelse($stockAlerts ?? [] as $alert)
                <div class="alert-item">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                    </div>
                    <div class="alert-content">
                        <h6>{{ $alert->nama_obat }}</h6>
                        <p>Stok: {{ $alert->stok }} {{ $alert->satuan }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <p>Tidak ada peringatan stok</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Access Information -->
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-info-circle me-2"></i>Informasi Akses</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-success">Akses yang Diizinkan:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Melihat dan mengelola data obat</li>
                        <li><i class="fas fa-check text-success me-2"></i>Melakukan transaksi penjualan</li>
                        <li><i class="fas fa-check text-success me-2"></i>Melihat data pelanggan</li>
                        <li><i class="fas fa-check text-success me-2"></i>Melihat laporan penjualan</li>
                        <li><i class="fas fa-check text-success me-2"></i>Mengelola spesifikasi obat</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-danger">Akses yang Dibatasi:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-times text-danger me-2"></i>Menambah/menghapus obat</li>
                        <li><i class="fas fa-times text-danger me-2"></i>Mengelola pembelian</li>
                        <li><i class="fas fa-times text-danger me-2"></i>Mengelola user admin</li>
                        <li><i class="fas fa-times text-danger me-2"></i>Mengakses laporan keuangan</li>
                        <li><i class="fas fa-times text-danger me-2"></i>Konfigurasi sistem</li>
                    </ul>
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

.stock-alerts .alert-item {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.stock-alerts .alert-item:last-child {
    border-bottom: none;
}

.alert-icon {
    margin-right: 15px;
    font-size: 1.2rem;
}

.alert-content h6 {
    margin: 0;
    font-size: 0.9rem;
    color: #333;
}

.alert-content p {
    margin: 5px 0 0 0;
    font-size: 0.8rem;
    color: #6c757d;
}
</style>
@endsection 