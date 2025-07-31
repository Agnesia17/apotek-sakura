@extends('admin.layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail Pelanggan</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.pelanggan.edit', $pelanggan->id_pelanggan) }}" class="btn btn-warning btn-sm">
                <i class="ti ti-edit"></i> Edit
            </a>
            <a href="{{ route('admin.pelanggan') }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <i class="ti ti-user text-primary" style="font-size: 80px;"></i>
                <h5 class="mt-2 mb-1">{{ $pelanggan->nama }}</h5>
                <p class="text-muted mb-0">{{ $pelanggan->username }}</p>
                <span class="badge bg-success mt-2">Aktif</span>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Telepon:</td>
                                <td><strong>{{ $pelanggan->telepon }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email:</td>
                                <td><strong>{{ $pelanggan->email ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Transaksi:</td>
                                <td><strong>{{ $penjualanCount }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Alamat:</td>
                                <td><strong>{{ $pelanggan->alamat ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Daftar:</td>
                                <td><strong>{{ $pelanggan->created_at ? $pelanggan->created_at->format('d/m/Y') : '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Terakhir Update:</td>
                                <td><strong>{{ $pelanggan->updated_at ? $pelanggan->updated_at->format('d/m/Y') : '-' }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form-{{ $pelanggan->id_pelanggan }}" action="{{ route('admin.pelanggan.destroy', $pelanggan->id_pelanggan) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete(id, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus pelanggan "${name}"?`)) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush