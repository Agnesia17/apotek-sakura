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

<!-- History Penjualan -->
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-history me-2"></i>History Penjualan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="historyPenjualanTable">
                    <thead>
                        <tr>
                            <th>ID Penjualan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions ?? [] as $transaction)
                        <tr>
                            <td>
                                <a href="{{ route('admin.penjualan.show', $transaction->id_penjualan) }}" class="text-primary fw-bold">
                                    #{{ $transaction->id_penjualan }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $transaction->tanggal ? $transaction->tanggal->format('d/m/Y') : 'N/A' }}</div>
                                <small class="text-muted">{{ $transaction->created_at ? $transaction->created_at->format('H:i') : '-' }}</small>
                            </td>
                            <td>
                                @if($transaction->pelanggan)
                                    <div class="fw-bold">{{ $transaction->pelanggan->nama }}</div>
                                    <small class="text-muted">{{ $transaction->pelanggan->telepon ?? '-' }}</small>
                                @else
                                    <span class="text-muted">Pelanggan Umum</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light-info">{{ $transaction->penjualanDetail->sum('jumlah') }} item</span>
                                <br>
                                <small class="text-muted">{{ $transaction->penjualanDetail->count() }} jenis</small>
                            </td>
                            <td>
                                <div class="fw-bold">Rp {{ number_format($transaction->total_harga - $transaction->diskon, 0, ',', '.') }}</div>
                                @if($transaction->diskon > 0)
                                    <small class="text-success">Diskon: Rp {{ number_format($transaction->diskon, 0, ',', '.') }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $transaction->status === 'selesai' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.penjualan.show', $transaction->id_penjualan) }}" 
                                   class="btn btn-outline-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr class="no-data-row">
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-shopping-cart fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted">Belum ada data penjualan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
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

.no-data-row td {
    border: none !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    try {
        var table = $('#historyPenjualanTable');
        var tableBody = table.find('tbody');
        
        // Check if table has data rows (excluding the no-data-row)
        var hasDataRows = tableBody.find('tr:not(.no-data-row)').length > 0;
        
        if (hasDataRows) {
            // Remove the no-data-row before initializing DataTable
            tableBody.find('.no-data-row').remove();
            
            // Verify table structure before initializing DataTable
            var headerCells = table.find('thead th').length;
            var firstDataRow = tableBody.find('tr:first td').length;
            
            if (headerCells === firstDataRow) {
                table.DataTable({
                    "pageLength": 10,
                    "order": [[ 1, "desc" ]], // Sort by date (newest first)
                    "language": {
                        "search": "Cari:",
                        "lengthMenu": "Tampilkan _MENU_ data",
                        "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        "paginate": {
                            "first": "Pertama",
                            "last": "Terakhir",
                            "next": "Selanjutnya",
                            "previous": "Sebelumnya"
                        }
                    }
                });
            } else {
                console.warn('Table structure mismatch. Header cells:', headerCells, 'Data cells:', firstDataRow);
                table.addClass('table-striped');
            }
        } else {
            // If no data, just add basic styling without DataTable
            table.addClass('table-striped');
        }
    } catch (error) {
        console.error('Error initializing DataTable:', error);
        // Fallback: just add basic styling
        $('#historyPenjualanTable').addClass('table-striped');
    }
});
</script>
@endpush
@endsection 