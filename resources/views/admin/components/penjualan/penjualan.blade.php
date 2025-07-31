@extends('admin.layouts.app')

@section('title', 'Daftar Penjualan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Penjualan</h5>
        <div class="d-flex gap-2">
            <a href="{{route('penjualan.create')}}" class="btn btn-primary btn-sm">
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
            <table class="table table-hover" id="penjualanTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $penjualan)
                        @php
                            $totalBayar = $penjualan->total_harga - $penjualan->diskon;
                            $totalItem = $penjualan->penjualanDetail->sum('jumlah');
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('penjualan.show', $penjualan->id_penjualan) }}" class="text-primary fw-bold">
                                    #{{ $penjualan->id_penjualan }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $penjualan->tanggal ? $penjualan->tanggal->format('d/m/Y') : 'N/A' }}</div>
                                <small class="text-muted">{{ $penjualan->created_at ? $penjualan->created_at->format('H:i') : '-' }}</small>
                            </td>
                            <td>
                                @if($penjualan->pelanggan)
                                    <div class="fw-bold">{{ $penjualan->pelanggan->nama }}</div>
                                    <small class="text-muted">{{ $penjualan->pelanggan->telepon ?? '-' }}</small>
                                @else
                                    <span class="text-muted">Pelanggan Umum</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $totalItem }} item</span>
                                <br>
                                <small class="text-muted">{{ $penjualan->penjualanDetail->count() }} jenis</small>
                            </td>
                            <td>
                                <div class="fw-bold">Rp {{ number_format($totalBayar, 0, ',', '.') }}</div>
                                @if($penjualan->diskon > 0)
                                    <small class="text-success">-{{ number_format(($penjualan->diskon / $penjualan->total_harga) * 100, 1) }}%</small>
                                @endif
                            </td>
                            <td>
                                <select class="form-select form-select-sm status-select" 
                                        data-id="{{ $penjualan->id_penjualan }}"
                                        data-current-status="{{ $penjualan->status }}">
                                    @foreach(\App\Models\Penjualan::getStatusOptions() as $key => $label)
                                        <option value="{{ $key }}" 
                                                {{ $penjualan->status === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <a href="{{ route('penjualan.show', $penjualan->id_penjualan) }}" 
                                   class="btn btn-outline-info btn-sm" title="Detail">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="no-data-row">
                            <td colspan="7" class="text-center py-4">
                                <i class="ti ti-shopping-cart fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted">Belum ada data penjualan</p>
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
// Handle status change
document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('.status-select');
    
    statusSelects.forEach(function(select) {
        select.addEventListener('change', function() {
            const penjualanId = this.dataset.id;
            const newStatus = this.value;
            const currentStatus = this.dataset.currentStatus;
            
            if (confirm(`Ubah status menjadi "${this.options[this.selectedIndex].text}"?`)) {
                updatePenjualanStatus(penjualanId, newStatus, this);
            } else {
                this.value = currentStatus;
            }
        });
    });
});

function updatePenjualanStatus(penjualanId, newStatus, selectElement) {
    selectElement.disabled = true;
    
    fetch(`/penjualan/${penjualanId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        selectElement.disabled = false;
        
        if (data.success) {
            selectElement.dataset.currentStatus = newStatus;
            showAlert('success', data.message);
        } else {
            selectElement.value = selectElement.dataset.currentStatus;
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        selectElement.disabled = false;
        selectElement.value = selectElement.dataset.currentStatus;
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan saat mengubah status');
    });
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(alertDiv, cardBody.firstChild);
    
    setTimeout(function() {
        if (alertDiv.parentNode) {
            const bsAlert = new bootstrap.Alert(alertDiv);
            bsAlert.close();
        }
    }, 5000);
}

$(document).ready(function() {
    try {
        var table = $('#penjualanTable');
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
        $('#penjualanTable').addClass('table-striped');
    }
});
</script>
@endpush