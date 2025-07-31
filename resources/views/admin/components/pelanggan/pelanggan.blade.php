@extends('admin.layouts.app')

@section('title', 'Daftar Pelanggan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Pelanggan</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.pelanggan.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus"></i> Tambah
            </a>
        </div>
    </div>
    
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover" id="pelangganTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Telepon</th>
                        <th>Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggans as $pelanggan)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-user text-primary me-2"></i>
                                    <div>
                                        <div class="fw-bold">{{ $pelanggan->nama }}</div>
                                        <small class="text-muted">{{ $pelanggan->penjualan()->count() }} transaksi</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $pelanggan->username }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $pelanggan->telepon }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $pelanggan->created_at ? $pelanggan->created_at->format('d/m/Y') : '-' }}</div>
                                <small class="text-muted">{{ $pelanggan->created_at ? $pelanggan->created_at->format('H:i') : '-' }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.pelanggan.show', $pelanggan->id_pelanggan) }}" 
                                       class="btn btn-outline-secondary" title="Detail">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.pelanggan.edit', $pelanggan->id_pelanggan) }}" 
                                       class="btn btn-outline-secondary" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-secondary" 
                                            title="Hapus"
                                            onclick="confirmDelete({{ $pelanggan->id_pelanggan }}, '{{ $pelanggan->nama }}')">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="ti ti-user fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted">Belum ada data pelanggan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
function confirmDelete(id, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus pelanggan "${name}"?`)) {
        document.getElementById('delete-form-' + id).submit();
    }
}

$(document).ready(function() {
    try {
        var table = $('#pelangganTable');
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
        $('#pelangganTable').addClass('table-striped');
    }
});
</script>
@endpush