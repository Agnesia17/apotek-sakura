@extends('admin.layouts.app')

@section('title', 'Daftar Obat')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Obat</h5>
        <div class="d-flex gap-2">
            @if(auth()->user()->role === 'superadmin')
            <a href="{{ route('admin.obat.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus"></i> Tambah
            </a>
            @endif
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
            <table class="table table-hover" id="obatTable">
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($obats as $obat)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-pill text-primary me-2"></i>
                                    <div>
                                        <div class="fw-bold">{{ $obat->nama_obat }}</div>
                                        <small class="text-muted">{{ $obat->brand }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $obat->kategori }}</span>
                            </td>
                            <td>
                                @if($obat->stok <= 10)
                                    <span class="text-danger fw-bold">{{ $obat->stok }}</span>
                                @elseif($obat->stok <= 50)
                                    <span class="text-warning fw-bold">{{ $obat->stok }}</span>
                                @else
                                    <span class="text-success fw-bold">{{ $obat->stok }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                @if($obat->isExpired())
                                    <span class="badge bg-danger">Kadaluarsa</span>
                                @elseif($obat->isAboutToExpire())
                                    <span class="badge bg-warning">Akan Kadaluarsa</span>
                                @else
                                    <span class="badge bg-success">Aman</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.obat.show', $obat->id_obat) }}" 
                                       class="btn btn-outline-secondary" title="Detail">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    @if(auth()->user()->role === 'superadmin')
                                    <button type="button" 
                                            class="btn btn-outline-secondary" 
                                            title="Hapus"
                                            onclick="confirmDelete({{ $obat->id_obat }}, '{{ $obat->nama_obat }}')">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="no-data-row">
                            <td colspan="6" class="text-center py-4">
                                <i class="ti ti-pill fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted">Belum ada data obat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Hapus obat <strong id="obatName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
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
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.05);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
function confirmDelete(id, name) {
    document.getElementById('obatName').textContent = name;
    document.getElementById('deleteForm').action = "{{ route('admin.obat.destroy', ':id') }}".replace(':id', id);
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

$(document).ready(function() {
    try {
        var table = $('#obatTable');
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
        $('#obatTable').addClass('table-striped');
    }
});
</script>
@endpush