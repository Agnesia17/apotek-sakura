@extends('admin.layouts.app')
@section('title', 'Edit Supplier')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Supplier</h5>
        <a href="{{ route('admin.supplier') }}" class="btn btn-secondary btn-sm">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="card-body">
        <form action="{{ route('admin.supplier.update', $supplier->id_supplier) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_supplier" class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" 
                           id="nama_supplier" name="nama_supplier" value="{{ old('nama_supplier', $supplier->nama_supplier) }}" required>
                    @error('nama_supplier')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="kota" class="form-label">Kota <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kota') is-invalid @enderror" 
                           id="kota" name="kota" value="{{ old('kota', $supplier->kota) }}" required>
                    @error('kota')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="telepon" class="form-label">Telepon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('telepon') is-invalid @enderror" 
                           id="telepon" name="telepon" value="{{ old('telepon', $supplier->telepon) }}" required>
                    @error('telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" 
                              id="alamat" name="alamat" rows="3" 
                              placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $supplier->alamat) }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.supplier') }}" class="btn btn-secondary">
                    <i class="ti ti-x"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format phone number
    const phoneInput = document.getElementById('telepon');
    phoneInput.addEventListener('input', function() {
        // Allow numbers, spaces, dashes, parentheses, and plus signs
        let value = this.value.replace(/[^\d\s\-\(\)\+]/g, '');
        
        // Limit to 20 characters
        if (value.length > 20) {
            value = value.slice(0, 20);
        }
        
        this.value = value;
    });


});
</script>
@endpush 