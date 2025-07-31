@extends('admin.layouts.app')
@section('title', 'Tambah Supplier')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tambah Supplier</h5>
        <a href="{{ route('admin.supplier') }}" class="btn btn-secondary btn-sm">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="card-body">
        <form action="{{ route('admin.supplier.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_supplier" class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" 
                           id="nama_supplier" name="nama_supplier" value="{{ old('nama_supplier') }}" required>
                    @error('nama_supplier')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="kota" class="form-label">Kota <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kota') is-invalid @enderror" 
                           id="kota" name="kota" value="{{ old('kota') }}" required>
                    @error('kota')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="telepon" class="form-label">Telepon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('telepon') is-invalid @enderror" 
                           id="telepon" name="telepon" value="{{ old('telepon') }}" required>
                    @error('telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" 
                              id="alamat" name="alamat" rows="3" 
                              placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
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
                    <i class="ti ti-plus"></i> Simpan
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

    // Capitalize first letter of city and supplier name
    const kotaInput = document.getElementById('kota');
    const namaSupplierInput = document.getElementById('nama_supplier');
    
    function capitalizeWords(input) {
        input.addEventListener('input', function() {
            let words = this.value.split(' ');
            words = words.map(word => {
                if (word.length > 0) {
                    return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
                }
                return word;
            });
            this.value = words.join(' ');
        });
    }
    
    capitalizeWords(kotaInput);
    capitalizeWords(namaSupplierInput);

    // Character counter for alamat
    const alamatInput = document.getElementById('alamat');
    const maxLength = 500;
    
    // Create character counter
    const counterDiv = document.createElement('div');
    counterDiv.className = 'form-text text-muted';
    counterDiv.innerHTML = `<span id="alamatCounter">0</span>/${maxLength} karakter`;
    alamatInput.parentNode.appendChild(counterDiv);
    
    alamatInput.addEventListener('input', function() {
        const currentLength = this.value.length;
        const counter = document.getElementById('alamatCounter');
        counter.textContent = currentLength;
        
        if (currentLength > maxLength * 0.9) {
            counter.parentNode.className = 'form-text text-warning';
        } else if (currentLength >= maxLength) {
            counter.parentNode.className = 'form-text text-danger';
        } else {
            counter.parentNode.className = 'form-text text-muted';
        }
    });

    // Form validation enhancement
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const namaSupplier = document.getElementById('nama_supplier').value.trim();
        const kota = document.getElementById('kota').value.trim();
        const telepon = document.getElementById('telepon').value.trim();
        const alamat = document.getElementById('alamat').value.trim();
        
        let isValid = true;
        let errorMessage = '';
        
        if (namaSupplier.length < 3) {
            isValid = false;
            errorMessage += 'Nama supplier minimal 3 karakter.\n';
        }
        
        if (kota.length < 2) {
            isValid = false;
            errorMessage += 'Nama kota minimal 2 karakter.\n';
        }
        
        if (telepon.length < 8) {
            isValid = false;
            errorMessage += 'Nomor telepon minimal 8 karakter.\n';
        }
        
        if (alamat.length < 10) {
            isValid = false;
            errorMessage += 'Alamat minimal 10 karakter.\n';
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Validasi gagal:\n\n' + errorMessage);
        }
    });
});
</script>
@endpush