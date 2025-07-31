@extends('admin.layouts.app')
@section('title', 'Detail Supplier')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail Supplier</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.supplier.edit', $supplier->id_supplier) }}" class="btn btn-warning btn-sm">
                <i class="ti ti-edit"></i> Edit
            </a>
            <a href="{{ route('admin.supplier') }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <i class="ti ti-building-store text-primary" style="font-size: 80px;"></i>
                <h5 class="mt-2 mb-1">{{ $supplier->nama_supplier }}</h5>
                <p class="text-muted mb-0">{{ $supplier->kota }}</p>
                <span class="badge bg-success mt-2">Aktif</span>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Telepon:</td>
                                <td><strong>{{ $supplier->telepon }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email:</td>
                                <td><strong>{{ $supplier->email ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Website:</td>
                                <td><strong>{{ $supplier->website ?? '-' }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Alamat:</td>
                                <td><strong>{{ $supplier->alamat ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Daftar:</td>
                                <td><strong>{{ $supplier->created_at ? $supplier->created_at->format('d/m/Y') : '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Terakhir Update:</td>
                                <td><strong>{{ $supplier->updated_at ? $supplier->updated_at->format('d/m/Y') : '-' }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form-{{ $supplier->id_supplier }}" action="{{ route('admin.supplier.destroy', $supplier->id_supplier) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete(id, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus supplier "${name}"?`)) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush