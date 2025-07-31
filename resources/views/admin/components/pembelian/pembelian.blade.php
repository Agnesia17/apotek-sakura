@extends('admin.layouts.app')

@section('title', 'Daftar Pembelian')

@section('content')
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Content -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Data Pembelian</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload()">
                            <i class="ti ti-refresh"></i> Refresh
                        </button>
                        <a href="{{route('pembelian.create')}}" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Tambah Transaksi
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="pembelianTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>TANGGAL</th>
                                    <th>SUPPLIER</th>
                                    <th>ITEM</th>
                                    <th>TOTAL</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembelians as $pembelian)
                                    @php
                                        $totalBayar = $pembelian->total_harga - $pembelian->diskon;
                                        $totalItem = $pembelian->pembelianDetail->sum('jumlah');
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('pembelian.show', $pembelian->id_pembelian) }}" class="text-primary fw-bold">
                                                #{{ $pembelian->id_pembelian }}
                                            </a>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $pembelian->tanggal ? $pembelian->tanggal->format('d/m/Y') : 'N/A' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $pembelian->created_at ? $pembelian->created_at->format('H:i') : '-' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($pembelian->supplier)
                                                <div>
                                                    <h6 class="mb-0">{{ $pembelian->supplier->nama_supplier }}</h6>
                                                    <small class="text-muted">{{ $pembelian->supplier->telepon ?? '-' }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">Supplier Tidak Diketahui</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light-info">
                                                {{ $totalItem }} item
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $pembelian->pembelianDetail->count() }} jenis obat</small>
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-primary">Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('pembelian.show', $pembelian->id_pembelian) }}" 
                                                   class="btn btn-outline-secondary" 
                                                   title="Lihat Detail">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="no-data-row">
                                        <td colspan="6" class="text-center py-4">
                                            <i class="ti ti-shopping-cart fs-1 text-muted d-block mb-2"></i>
                                            <p class="text-muted">Belum ada data pembelian</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
.btn-group.btn-group-sm > .btn {
    margin-right: 0.5rem;
}
.btn-group.btn-group-sm > .btn:last-child {
    margin-right: 0;
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
        var table = $('#pembelianTable');
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
        $('#pembelianTable').addClass('table-striped');
    }
});
</script>
@endpush