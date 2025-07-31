@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail Obat</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('obat.index') }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
            @if(auth()->user()->role === 'superadmin')
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal">
                <i class="ti ti-edit"></i> Edit
            </button>
            @endif
        </div>
    </div>
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center mb-3">
                <i class="ti ti-pill text-primary" style="font-size: 4rem;"></i>
                <h4 class="mt-2">{{ $obat->nama_obat }}</h4>
                <div class="mb-2">
                    <span class="badge bg-primary">{{ $obat->kategori }}</span>
                    @if($obat->brand)
                        <span class="badge bg-secondary">{{ $obat->brand }}</span>
                    @endif
                </div>
                @if($obat->isExpired())
                    <span class="badge bg-danger">Kadaluarsa</span>
                @elseif($obat->isAboutToExpire())
                    <span class="badge bg-warning">Akan Kadaluarsa</span>
                @else
                    <span class="badge bg-success">Aman</span>
                @endif
            </div>
            
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Satuan:</label>
                        <p>{{ $obat->satuan }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Stok:</label>
                        <p>
                            @if($obat->stok <= 10)
                                <span class="text-danger fw-bold">{{ $obat->stok }}</span>
                            @elseif($obat->stok <= 50)
                                <span class="text-warning fw-bold">{{ $obat->stok }}</span>
                            @else
                                <span class="text-success fw-bold">{{ $obat->stok }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Harga Beli:</label>
                        <p>Rp {{ number_format($obat->harga_beli, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Harga Jual:</label>
                        <p>Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Supplier:</label>
                        <p>{{ $obat->supplier->nama_supplier ?? 'Tidak ada' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Tanggal Kadaluarsa:</label>
                        <p>{{ $obat->tanggal_kadaluarsa ? $obat->tanggal_kadaluarsa->format('d/m/Y') : 'Tidak ada data' }}</p>
                    </div>
                </div>
                
                @if($obat->deskripsi)
                <div class="mb-3">
                    <label class="fw-bold">Deskripsi:</label>
                    <p>{{ $obat->deskripsi }}</p>
                </div>
                @endif

                @if($obat->spesifikasi)
                <div class="mb-3">
                    <label class="fw-bold">Spesifikasi:</label>
                    <div class="row">
                        @if($obat->spesifikasi->kandungan)
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Kandungan:</small><br>
                            {{ $obat->spesifikasi->kandungan }}
                        </div>
                        @endif
                        @if($obat->spesifikasi->bentuk_sediaan)
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Bentuk Sediaan:</small><br>
                            {{ $obat->spesifikasi->bentuk_sediaan }}
                        </div>
                        @endif
                        @if($obat->spesifikasi->kemasan)
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Kemasan:</small><br>
                            {{ $obat->spesifikasi->kemasan }}
                        </div>
                        @endif
                        @if($obat->spesifikasi->cara_kerja)
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Cara Kerja:</small><br>
                            {{ $obat->spesifikasi->cara_kerja }}
                        </div>
                        @endif
                        @if($obat->spesifikasi->penyimpanan)
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Penyimpanan:</small><br>
                            {{ $obat->spesifikasi->penyimpanan }}
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i>
                    Spesifikasi detail belum ditambahkan.
                    <button type="button" class="btn btn-sm btn-outline-primary ms-2" 
                            data-bs-toggle="modal" data-bs-target="#editModal" 
                            onclick="showSpesifikasiForm()">
                        Tambah Spesifikasi
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Obat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="editTab" role="tablist">
                    @if(auth()->user()->role === 'superadmin')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="data-obat-tab" data-bs-toggle="tab" 
                                data-bs-target="#data-obat" type="button" role="tab">
                            Data Obat
                        </button>
                    </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ auth()->user()->role !== 'superadmin' ? 'active' : '' }}" id="spesifikasi-tab" data-bs-toggle="tab" 
                                data-bs-target="#spesifikasi" type="button" role="tab">
                            Spesifikasi
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="editTabContent">
                    @if(auth()->user()->role === 'superadmin')
                    <div class="tab-pane fade show active" id="data-obat" role="tabpanel">
                        <form action="{{ route('obat.update', $obat->id_obat) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_nama_obat" class="form-label">Nama Obat</label>
                                    <input type="text" class="form-control" id="edit_nama_obat" 
                                           name="nama_obat" value="{{ $obat->nama_obat }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_kategori" class="form-label">Kategori</label>
                                    <select class="form-select" id="edit_kategori" name="kategori" required>
                                        <option value="Antibiotik" {{ $obat->kategori == 'Antibiotik' ? 'selected' : '' }}>Antibiotik</option>
                                        <option value="Analgesik" {{ $obat->kategori == 'Analgesik' ? 'selected' : '' }}>Analgesik</option>
                                        <option value="Antasida" {{ $obat->kategori == 'Antasida' ? 'selected' : '' }}>Antasida</option>
                                        <option value="Vitamin" {{ $obat->kategori == 'Vitamin' ? 'selected' : '' }}>Vitamin</option>
                                        <option value="Suplemen" {{ $obat->kategori == 'Suplemen' ? 'selected' : '' }}>Suplemen</option>
                                        <option value="Antiseptik" {{ $obat->kategori == 'Antiseptik' ? 'selected' : '' }}>Antiseptik</option>
                                        <option value="Lainnya" {{ $obat->kategori == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_brand" class="form-label">Brand</label>
                                    <input type="text" class="form-control" id="edit_brand" 
                                           name="brand" value="{{ $obat->brand }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_satuan" class="form-label">Satuan</label>
                                    <select class="form-select" id="edit_satuan" name="satuan" required>
                                        <option value="Strip" {{ $obat->satuan == 'Strip' ? 'selected' : '' }}>Strip</option>
                                        <option value="Box" {{ $obat->satuan == 'Box' ? 'selected' : '' }}>Box</option>
                                        <option value="Botol" {{ $obat->satuan == 'Botol' ? 'selected' : '' }}>Botol</option>
                                        <option value="Tablet" {{ $obat->satuan == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                        <option value="Kapsul" {{ $obat->satuan == 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                                        <option value="Tube" {{ $obat->satuan == 'Tube' ? 'selected' : '' }}>Tube</option>
                                        <option value="Pcs" {{ $obat->satuan == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_harga_beli" class="form-label">Harga Beli</label>
                                    <input type="number" step="0.01" class="form-control" id="edit_harga_beli" 
                                           name="harga_beli" value="{{ $obat->harga_beli }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_harga_jual" class="form-label">Harga Jual</label>
                                    <input type="number" step="0.01" class="form-control" id="edit_harga_jual" 
                                           name="harga_jual" value="{{ $obat->harga_jual }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_tanggal_kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
                                    <input type="date" class="form-control" id="edit_tanggal_kadaluarsa" 
                                           name="tanggal_kadaluarsa" value="{{ $obat->tanggal_kadaluarsa?->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_id_supplier" class="form-label">Supplier</label>
                                    <select class="form-select" id="edit_id_supplier" name="id_supplier" required>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id_supplier }}" 
                                                    {{ $obat->id_supplier == $supplier->id_supplier ? 'selected' : '' }}>
                                                {{ $supplier->nama_supplier }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3">{{ $obat->deskripsi }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                    @endif

                    <div class="tab-pane fade {{ auth()->user()->role !== 'superadmin' ? 'show active' : '' }}" id="spesifikasi" role="tabpanel">
                        <form action="{{ route('obat.spesifikasi.store', $obat->id_obat) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="kandungan" class="form-label">Kandungan</label>
                                    <textarea class="form-control" id="kandungan" name="kandungan" rows="2">{{ $obat->spesifikasi->kandungan ?? '' }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bentuk_sediaan" class="form-label">Bentuk Sediaan</label>
                                    <input type="text" class="form-control" id="bentuk_sediaan" name="bentuk_sediaan" value="{{ $obat->spesifikasi->bentuk_sediaan ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="kemasan" class="form-label">Kemasan</label>
                                    <input type="text" class="form-control" id="kemasan" name="kemasan" value="{{ $obat->spesifikasi->kemasan ?? '' }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="cara_kerja" class="form-label">Cara Kerja</label>
                                    <textarea class="form-control" id="cara_kerja" name="cara_kerja" rows="2">{{ $obat->spesifikasi->cara_kerja ?? '' }}</textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="penyimpanan" class="form-label">Penyimpanan</label>
                                    <textarea class="form-control" id="penyimpanan" name="penyimpanan" rows="2">{{ $obat->spesifikasi->penyimpanan ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Simpan Spesifikasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showSpesifikasiForm() {
    setTimeout(() => {
        document.getElementById('spesifikasi-tab').click();
    }, 300);
}
</script>
@endpush