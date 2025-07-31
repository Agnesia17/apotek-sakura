@extends('admin.layouts.app')
@section('title', 'Tambah Obat')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Obat Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('obat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_obat" class="form-label">Nama Obat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_obat') is-invalid @enderror" 
                           id="nama_obat" name="nama_obat" value="{{ old('nama_obat') }}" required>
                    @error('nama_obat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Antibiotik" {{ old('kategori') == 'Antibiotik' ? 'selected' : '' }}>Antibiotik</option>
                        <option value="Analgesik" {{ old('kategori') == 'Analgesik' ? 'selected' : '' }}>Analgesik</option>
                        <option value="Antasida" {{ old('kategori') == 'Antasida' ? 'selected' : '' }}>Antasida</option>
                        <option value="Vitamin" {{ old('kategori') == 'Vitamin' ? 'selected' : '' }}>Vitamin</option>
                        <option value="Suplemen" {{ old('kategori') == 'Suplemen' ? 'selected' : '' }}>Suplemen</option>
                        <option value="Antiseptik" {{ old('kategori') == 'Antiseptik' ? 'selected' : '' }}>Antiseptik</option>
                        <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="brand" class="form-label">Brand/Merek</label>
                    <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                           id="brand" name="brand" value="{{ old('brand') }}">
                    @error('brand')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                    <select class="form-select @error('satuan') is-invalid @enderror" id="satuan" name="satuan" required>
                        <option value="">Pilih Satuan</option>
                        <option value="Strip" {{ old('satuan') == 'Strip' ? 'selected' : '' }}>Strip</option>
                        <option value="Box" {{ old('satuan') == 'Box' ? 'selected' : '' }}>Box</option>
                        <option value="Botol" {{ old('satuan') == 'Botol' ? 'selected' : '' }}>Botol</option>
                        <option value="Tablet" {{ old('satuan') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                        <option value="Kapsul" {{ old('satuan') == 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                        <option value="Tube" {{ old('satuan') == 'Tube' ? 'selected' : '' }}>Tube</option>
                        <option value="Pcs" {{ old('satuan') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                    </select>
                    @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="harga_beli" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" class="form-control @error('harga_beli') is-invalid @enderror" 
                               id="harga_beli" name="harga_beli" value="{{ old('harga_beli') }}" required>
                    </div>
                    @error('harga_beli')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="harga_jual" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" class="form-control @error('harga_jual') is-invalid @enderror" 
                               id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" required>
                    </div>
                    @error('harga_jual')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tanggal_kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
                    <input type="date" class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror" 
                           id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa') }}">
                    @error('tanggal_kadaluarsa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="id_supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                    <select class="form-select @error('id_supplier') is-invalid @enderror" id="id_supplier" name="id_supplier" required>
                        <option value="">Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id_supplier }}" 
                                    {{ old('id_supplier') == $supplier->id_supplier ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_supplier')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" name="deskripsi" rows="3" 
                              placeholder="Masukkan deskripsi obat">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('obat.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
                <div>
                    <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto calculate harga jual based on harga beli (with 20% markup)
    const hargaBeli = document.getElementById('harga_beli');
    const hargaJual = document.getElementById('harga_jual');
    
    hargaBeli.addEventListener('input', function() {
        if (this.value && !hargaJual.value) {
            const markup = parseFloat(this.value) * 1.2; // 20% markup
            hargaJual.value = markup.toFixed(0);
        }
    });
    
    // Validate tanggal kadaluarsa tidak boleh sebelum hari ini
    const tanggalKadaluarsa = document.getElementById('tanggal_kadaluarsa');
    const today = new Date().toISOString().split('T')[0];
    tanggalKadaluarsa.setAttribute('min', today);
});
</script>
@endpush