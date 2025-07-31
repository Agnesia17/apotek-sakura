@extends('admin.layouts.app')

@section('title', 'Daftar Obat Kadaluarsa')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Obat Kadaluarsa</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('obat.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="ti ti-arrow-left"></i> Kembali
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

        @if($expiredObats->count() > 0)
            <div class="alert alert-warning" role="alert">
                <i class="ti ti-alert-triangle me-2"></i>
                Ditemukan {{ $expiredObats->count() }} obat yang telah kadaluarsa.
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover" id="expiredObatTable">
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expiredObats as $obat)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-pill text-danger me-2"></i>
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
                                <span class="text-danger fw-bold">{{ $obat->stok }}</span>
                                @if($obat->stok > 0)
                                    <small class="text-danger d-block">unit tersisa</small>
                                @else
                                    <small class="text-muted d-block">Stok habis</small>
                                @endif
                            </td>
                            <td>
                                <div class="text-danger fw-bold">
                                    {{ $obat->tanggal_kadaluarsa ? $obat->tanggal_kadaluarsa->format('d/m/Y') : 'Tidak ada data' }}
                                </div>
                                <small class="text-muted">
                                    {{ $obat->tanggal_kadaluarsa->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-danger">
                                    <i class="ti ti-alert-triangle me-1"></i>
                                    KADALUARSA
                                </span>
                            </td>
                            <td>
                                @if(auth()->user()->role === 'superadmin')
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" 
                                                class="btn btn-warning" 
                                                title="Hapus Obat"
                                                onclick="confirmDeleteExpired({{ $obat->id_obat }}, '{{ $obat->nama_obat }}', '{{ $obat->tanggal_kadaluarsa ? $obat->tanggal_kadaluarsa->format('d/m/Y') : 'Tidak ada data' }}', false)">
                                            <i class="ti ti-archive"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                title="Hapus Paksa"
                                                onclick="confirmDeleteExpired({{ $obat->id_obat }}, '{{ $obat->nama_obat }}', '{{ $obat->tanggal_kadaluarsa ? $obat->tanggal_kadaluarsa->format('d/m/Y') : 'Tidak ada data' }}', true)">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted small">Akses Terbatas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="ti ti-check-circle fs-1 text-success d-block mb-2"></i>
                                <p class="text-success">Tidak Ada Obat Kadaluarsa</p>
                                <small class="text-muted">Semua obat dalam kondisi baik</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteExpiredModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="ti ti-alert-triangle me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert">
                    <i class="ti ti-alert-circle me-2"></i>
                    Obat ini telah kadaluarsa dan tidak layak untuk dikonsumsi.
                </div>
                <div id="deleteTypeInfo"></div>
                <p>Hapus obat kadaluarsa berikut?</p>
                <div class="bg-light p-3 rounded">
                    <p class="mb-1"><strong>Nama Obat:</strong> <span id="expiredObatName"></span></p>
                    <p class="mb-0"><strong>Tanggal Kadaluarsa:</strong> <span id="expiredObatDate" class="text-danger"></span></p>
                </div>
                <div id="deleteWarning" class="mt-2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteExpiredForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
function confirmDeleteExpired(id, name, expiryDate, isForceDelete = false) {
    document.getElementById('expiredObatName').textContent = name;
    document.getElementById('expiredObatDate').textContent = expiryDate;
    
    const deleteTypeInfo = document.getElementById('deleteTypeInfo');
    const deleteWarning = document.getElementById('deleteWarning');
    const deleteForm = document.getElementById('deleteExpiredForm');
    
    if (isForceDelete) {
        deleteForm.action = "{{ route('obat.forceDeleteExpired', ':id') }}".replace(':id', id);
        deleteTypeInfo.innerHTML = `
            <div class="alert alert-warning" role="alert">
                <i class="ti ti-alert-triangle me-2"></i>
                <strong>Mode: HAPUS PAKSA</strong><br>
                Obat akan dihentikan meski memiliki riwayat transaksi.
            </div>
        `;
        deleteWarning.innerHTML = `
            <p class="text-warning small">
                Jika obat memiliki riwayat transaksi, obat akan ditandai sebagai "DIHENTIKAN" 
                dengan stok direset ke 0.
            </p>
        `;
    } else {
        deleteForm.action = "{{ route('admin.obat.destroy', ':id') }}".replace(':id', id);
        deleteTypeInfo.innerHTML = `
            <div class="alert alert-info" role="alert">
                <i class="ti ti-info-circle me-2"></i>
                <strong>Mode: HAPUS AMAN</strong><br>
                Sistem akan mengecek riwayat transaksi terlebih dahulu.
            </div>
        `;
        deleteWarning.innerHTML = `
            <p class="text-muted small">
                Jika obat memiliki riwayat transaksi, stok akan direset ke 0 dan obat ditandai sebagai tidak aktif.
            </p>
        `;
    }
    
    new bootstrap.Modal(document.getElementById('deleteExpiredModal')).show();
}

$(document).ready(function() {
    $('#expiredObatTable').DataTable({
        "pageLength": 10,
        "order": [[ 3, "asc" ]], // Sort by expiry date (oldest first)
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
});
</script>
@endpush