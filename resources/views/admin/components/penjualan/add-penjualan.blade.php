@extends('admin.layouts.app')
@section('title', 'Tambah Transaksi Penjualan')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Transaksi Penjualan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('penjualan.store') }}" method="POST" id="penjualanForm">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                           id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="id_pelanggan" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                    <select class="form-select @error('id_pelanggan') is-invalid @enderror" id="id_pelanggan" name="id_pelanggan" required>
                        <option value="">Pilih Pelanggan</option>
                        @foreach($pelanggan as $customer)
                            <option value="{{ $customer->id_pelanggan }}" 
                                    {{ old('id_pelanggan') == $customer->id_pelanggan ? 'selected' : '' }}>
                                {{ $customer->nama }} - {{ $customer->telepon }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="diskon" class="form-label">Diskon</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" class="form-control @error('diskon') is-invalid @enderror" 
                               id="diskon" name="diskon" value="{{ old('diskon', 0) }}" min="0">
                    </div>
                    @error('diskon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Detail Obat -->
            <div class="mt-4">
                <h6 class="mb-3">Detail Obat</h6>
                <div class="table-responsive">
                    <table class="table table-bordered" id="obatTable">
                        <thead class="table-light">
                            <tr>
                                <th>Obat</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="obatTableBody">
                            <tr class="obat-row">
                                <td>
                                    <select class="form-select obat-select" name="obat[0][id_obat]" required>
                                        <option value="">Pilih Obat</option>
                                        @foreach($obat as $medicine)
                                            <option value="{{ $medicine->id_obat }}" 
                                                    data-harga="{{ $medicine->harga_jual }}"
                                                    data-stok="{{ $medicine->stok }}">
                                                {{ $medicine->nama_obat }} (Stok: {{ $medicine->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control harga-satuan" 
                                               name="obat[0][harga_satuan]" readonly>
                                    </div>
                                </td>
                                <td>
                                    <input type="number" class="form-control jumlah" 
                                           name="obat[0][jumlah]" min="1" required>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control subtotal" readonly>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row" disabled>
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-success btn-sm" id="addRow">
                    <i class="ti ti-plus"></i> Tambah Obat
                </button>
            </div>

            <!-- Total -->
            <div class="row mt-4">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="totalSubtotal">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Diskon:</span>
                                <span id="totalDiskon">Rp 0</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span id="grandTotal">Rp 0</span>
                            </div>
                            <input type="hidden" name="total_harga" id="totalHargaInput">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
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
    let rowIndex = 1;
    
    // Add new row
    document.getElementById('addRow').addEventListener('click', function() {
        const tableBody = document.getElementById('obatTableBody');
        const newRow = document.querySelector('.obat-row').cloneNode(true);
        
        // Update input names and reset values
        newRow.querySelectorAll('select, input').forEach(function(input) {
            if (input.name) {
                input.name = input.name.replace(/\[0\]/, `[${rowIndex}]`);
            }
            if (input.type !== 'button') {
                input.value = '';
            }
        });
        
        // Enable remove button
        newRow.querySelector('.remove-row').disabled = false;
        
        tableBody.appendChild(newRow);
        rowIndex++;
        updateRemoveButtons();
    });
    
    // Remove row functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('.obat-row').remove();
            updateRemoveButtons();
            calculateTotal();
        }
    });
    
    // Update remove button states
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.obat-row');
        rows.forEach(function(row, index) {
            const removeBtn = row.querySelector('.remove-row');
            removeBtn.disabled = rows.length === 1;
        });
    }
    
    // Handle obat selection
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('obat-select')) {
            const selectedOption = e.target.selectedOptions[0];
            const row = e.target.closest('.obat-row');
            const hargaInput = row.querySelector('.harga-satuan');
            const jumlahInput = row.querySelector('.jumlah');
            
            if (selectedOption.value) {
                hargaInput.value = selectedOption.dataset.harga;
                jumlahInput.max = selectedOption.dataset.stok;
                jumlahInput.value = 1;
            } else {
                hargaInput.value = '';
                jumlahInput.value = '';
                jumlahInput.removeAttribute('max');
            }
            calculateRowSubtotal(row);
        }
    });
    
    // Handle quantity change
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('jumlah')) {
            const row = e.target.closest('.obat-row');
            calculateRowSubtotal(row);
        }
        
        if (e.target.id === 'diskon') {
            calculateTotal();
        }
    });
    
    // Calculate row subtotal
    function calculateRowSubtotal(row) {
        const harga = parseFloat(row.querySelector('.harga-satuan').value) || 0;
        const jumlah = parseInt(row.querySelector('.jumlah').value) || 0;
        const subtotal = harga * jumlah;
        
        row.querySelector('.subtotal').value = subtotal;
        calculateTotal();
    }
    
    // Calculate grand total
    function calculateTotal() {
        let totalSubtotal = 0;
        
        document.querySelectorAll('.subtotal').forEach(function(input) {
            totalSubtotal += parseFloat(input.value) || 0;
        });
        
        const diskon = parseFloat(document.getElementById('diskon').value) || 0;
        const grandTotal = totalSubtotal - diskon;
        
        // Update display
        document.getElementById('totalSubtotal').textContent = 'Rp ' + totalSubtotal.toLocaleString('id-ID');
        document.getElementById('totalDiskon').textContent = 'Rp ' + diskon.toLocaleString('id-ID');
        document.getElementById('grandTotal').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
        
        // Update hidden input
        document.getElementById('totalHargaInput').value = grandTotal;
    }
    
    // Form validation
    document.getElementById('penjualanForm').addEventListener('submit', function(e) {
        const rows = document.querySelectorAll('.obat-row');
        let hasValidRow = false;
        
        rows.forEach(function(row) {
            const obatSelect = row.querySelector('.obat-select');
            const jumlahInput = row.querySelector('.jumlah');
            
            if (obatSelect.value && jumlahInput.value) {
                hasValidRow = true;
            }
        });
        
        if (!hasValidRow) {
            e.preventDefault();
            alert('Minimal harus ada satu obat yang dipilih!');
        }
        
        const grandTotal = parseFloat(document.getElementById('totalHargaInput').value) || 0;
        if (grandTotal <= 0) {
            e.preventDefault();
            alert('Total transaksi harus lebih dari 0!');
        }
    });
    
    // Initialize
    updateRemoveButtons();
});
</script>
@endpush