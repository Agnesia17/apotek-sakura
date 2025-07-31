@extends('admin.layouts.app')
@section('title', 'Detail Apoteker')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail Apoteker</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.apoteker.edit', $apoteker->id) }}" class="btn btn-warning btn-sm">
                <i class="ti ti-edit"></i> Edit
            </a>
            <a href="{{ route('admin.apoteker.index') }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <i class="ti ti-user-md text-primary" style="font-size: 80px;"></i>
                <h5 class="mt-2 mb-1">{{ $apoteker->name }}</h5>
                <p class="text-muted mb-0">{{ $apoteker->role === 'apoteker' ? 'Apoteker' : ucfirst($apoteker->role) }}</p>
                <span class="badge bg-success mt-2">Aktif</span>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Email:</td>
                                <td><strong>{{ $apoteker->email }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Telepon:</td>
                                <td><strong>{{ $apoteker->phone ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Username:</td>
                                <td><strong>{{ $apoteker->username ?? '-' }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Alamat:</td>
                                <td><strong>{{ $apoteker->address ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Bergabung:</td>
                                <td><strong>{{ $apoteker->created_at ? $apoteker->created_at->format('d/m/Y') : '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Terakhir Update:</td>
                                <td><strong>{{ $apoteker->updated_at ? $apoteker->updated_at->format('d/m/Y') : '-' }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form-{{ $apoteker->id }}" action="{{ route('admin.apoteker.destroy', $apoteker->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete(id, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus apoteker "${name}"?`)) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush