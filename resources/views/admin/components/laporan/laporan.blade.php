@extends('admin.layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter Controls -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Filter Laporan</h6>
                </div>
                <div class="card-body">
                    <form id="filterForm" method="GET" action="{{ route('admin.laporan.show') }}">
                        <div class="row g-3">
                            <!-- Type Selection -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Jenis Laporan</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="type" id="type_penjualan" value="penjualan" 
                                           {{ ($type ?? 'penjualan') === 'penjualan' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary" for="type_penjualan">
                                        <i class="ti ti-shopping-cart me-1"></i>Penjualan
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="type" id="type_pembelian" value="pembelian"
                                           {{ ($type ?? 'penjualan') === 'pembelian' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary" for="type_pembelian">
                                        <i class="ti ti-shopping-bag me-1"></i>Pembelian
                                    </label>
                                </div>
                            </div>

                            <!-- Period Selection -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Periode</label>
                                <select class="form-select" name="period" id="period">
                                    <option value="today" {{ ($period ?? 'month') === 'today' ? 'selected' : '' }}>Hari Ini</option>
                                    <option value="week" {{ ($period ?? 'month') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
                                    <option value="month" {{ ($period ?? 'month') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="year" {{ ($period ?? 'month') === 'year' ? 'selected' : '' }}>Tahun Ini</option>
                                    <option value="custom" {{ ($period ?? 'month') === 'custom' ? 'selected' : '' }}>Kustom</option>
                                </select>
                            </div>

                            <!-- Filter Button -->
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-filter me-1"></i>Filter Data
                                </button>
                            </div>

                            <!-- Custom Date Range -->
                            <div class="col-12" id="customDateRange" style="{{ ($period ?? 'month') === 'custom' ? '' : 'display: none;' }}">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Dari Tanggal</label>
                                        <input type="date" class="form-control" name="start_date" 
                                               value="{{ request('start_date') ?? ($startDate ? $startDate->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Sampai Tanggal</label>
                                        <input type="date" class="form-control" name="end_date" 
                                               value="{{ request('end_date') ?? ($endDate ? $endDate->format('Y-m-d') : '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    @if(isset($totalTransaksi))
    <div class="row mb-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ number_format($totalTransaksi) }}</h4>
                            <p class="text-muted mb-0">Total Transaksi</p>
                        </div>
                        <div class="ms-3">
                            <i class="ti ti-file-text text-primary fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">Rp {{ number_format($totalNilai ?? 0, 0, ',', '.') }}</h4>
                            <p class="text-muted mb-0">Total Nilai</p>
                        </div>
                        <div class="ms-3">
                            <i class="ti ti-currency-dollar text-success fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ number_format($totalItem ?? 0) }}</h4>
                            <p class="text-muted mb-0">Total Item</p>
                        </div>
                        <div class="ms-3">
                            <i class="ti ti-package text-info fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">Rp {{ $totalTransaksi > 0 ? number_format(($totalNilai ?? 0) / $totalTransaksi, 0, ',', '.') : '0' }}</h4>
                            <p class="text-muted mb-0">Rata-rata</p>
                        </div>
                        <div class="ms-3">
                            <i class="ti ti-chart-line text-warning fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        Data {{ ($type ?? 'penjualan') === 'penjualan' ? 'Penjualan' : 'Pembelian' }}
                        @if(isset($startDate) && isset($endDate))
                            <small class="text-muted">
                                ({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})
                            </small>
                        @endif
                    </h5>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload()">
                        <i class="ti ti-refresh"></i> Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if(($type ?? 'penjualan') === 'penjualan')
                            <!-- Penjualan Table -->
                            <table class="table table-hover" id="laporanTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>TANGGAL</th>
                                        <th>PELANGGAN</th>
                                        <th>ITEM</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($penjualans ?? [] as $penjualan)
                                        <tr>
                                            <td>
                                                <span class="text-muted">#{{ $penjualan->id_penjualan }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $penjualan->created_at ? $penjualan->created_at->format('d/m/Y') : '-' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $penjualan->created_at ? $penjualan->created_at->format('H:i') : '-' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($penjualan->pelanggan)
                                                    <div>
                                                        <h6 class="mb-0">{{ $penjualan->pelanggan->nama }}</h6>
                                                        <small class="text-muted">{{ $penjualan->pelanggan->telepon }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Guest</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light-info">{{ $penjualan->penjualanDetail->count() }} item</span>
                                                <br>
                                                <small class="text-muted">{{ $penjualan->penjualanDetail->sum('jumlah') }} qty</small>
                                            </td>
                                            <td>
                                                <strong>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="no-data-row">
                                            <td colspan="5" class="text-center py-4">
                                                <i class="ti ti-file-text fs-1 text-muted d-block mb-2"></i>
                                                <p class="text-muted">Belum ada data penjualan</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @else
                            <!-- Pembelian Table -->
                            <table class="table table-hover" id="laporanTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>TANGGAL</th>
                                        <th>SUPPLIER</th>
                                        <th>ITEM</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pembelians ?? [] as $pembelian)
                                        <tr>
                                            <td>
                                                <span class="text-muted">#{{ $pembelian->id_pembelian }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $pembelian->created_at ? $pembelian->created_at->format('d/m/Y') : '-' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $pembelian->created_at ? $pembelian->created_at->format('H:i') : '-' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($pembelian->supplier)
                                                    <div>
                                                        <h6 class="mb-0">{{ $pembelian->supplier->nama_supplier }}</h6>
                                                        <small class="text-muted">{{ $pembelian->supplier->kota }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light-info">{{ $pembelian->pembelianDetail->count() }} item</span>
                                                <br>
                                                <small class="text-muted">{{ $pembelian->pembelianDetail->sum('jumlah') }} qty</small>
                                            </td>
                                            <td>
                                                <strong>Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="no-data-row">
                                            <td colspan="5" class="text-center py-4">
                                                <i class="ti ti-file-text fs-1 text-muted d-block mb-2"></i>
                                                <p class="text-muted">Belum ada data pembelian</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
.btn-check:checked + .btn {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

.badge.bg-light-info {
    background-color: rgba(23, 162, 184, 0.1) !important;
    color: #17a2b8;
}

.no-data-row td {
    border: none !important;
}

.card {
    border: 1px solid rgba(0,0,0,.125);
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
}

.card-header {
    background-color: rgba(0,0,0,.03);
    border-bottom: 1px solid rgba(0,0,0,.125);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
// Show/hide custom date range
document.getElementById('period').addEventListener('change', function() {
    const customDateRange = document.getElementById('customDateRange');
    if (this.value === 'custom') {
        customDateRange.style.display = '';
    } else {
        customDateRange.style.display = 'none';
    }
});

// Auto submit form when type changes
document.querySelectorAll('input[name="type"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});

// Auto submit form when period changes (except custom)
document.getElementById('period').addEventListener('change', function() {
    if (this.value !== 'custom') {
        document.getElementById('filterForm').submit();
    }
});

// DataTables Initialization
$(document).ready(function() {
    try {
        var table = $('#laporanTable');
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
                    "pageLength": 15,
                    "order": [[1, 'desc']], // Sort by date descending
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
        $('#laporanTable').addClass('table-striped');
    }
});
</script>
@endpush