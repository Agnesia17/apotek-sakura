@extends('admin.layouts.app')
@section('title', 'Tambah Transaksi Pembelian')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tambah Transaksi Pembelian</h5>
        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary btn-sm">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('pembelian.store') }}" method="POST" id="pembelianForm">
            @csrf
            <div class="row">
                <!-- Tanggal Transaksi -->
                <div class="col-md-4 mb-3">
                    <label for="tanggal" class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                           id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Supplier -->
                <div class="col-md-4 mb-3">
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

                <!-- Diskon -->
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

            <!-- Detail Obat Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="mb-3">Detail Obat</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="obatTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Obat</th>
                                    <th>Harga Beli</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="obatTableBody">
                                <tr class="obat-row">
                                    <td>
                                        <select class="form-select obat-select" name="obat[0][id_obat]" required disabled>
                                            <option value="">Pilih Supplier Terlebih Dahulu</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control harga-beli" 
                                                   name="obat[0][harga_beli]" readonly>
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
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button type="button" class="btn btn-success btn-sm" id="addRow">
                            <i class="ti ti-plus"></i> Tambah Obat
                        </button>
                    </div>
                </div>
            </div>

            <!-- Total Section -->
            <div class="row mt-4">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="card border">
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

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">
                    <i class="ti ti-x"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Pass obat data to JavaScript
const obatData = @json($obatJson);

document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;
    let selectedSupplierId = null;
    
    // Handle supplier selection
    document.getElementById('id_supplier').addEventListener('change', function() {
        selectedSupplierId = this.value;
        updateObatDropdowns();
    });
    
    // Function to update obat dropdowns based on selected supplier
    function updateObatDropdowns(clearData = true) {
        const obatSelects = document.querySelectorAll('.obat-select');
        
        obatSelects.forEach(function(select) {
            // Store current selection if preserving data
            const currentValue = select.value;
            
            // Clear existing options
            select.innerHTML = '';
            
            if (!selectedSupplierId) {
                select.innerHTML = '<option value="">Pilih Supplier Terlebih Dahulu</option>';
                select.disabled = true;
                return;
            }
            
            // Enable dropdown and add default option
            select.disabled = false;
            select.innerHTML = '<option value="">Pilih Obat</option>';
            
            // Filter obat by supplier and add to dropdown
            const filteredObat = obatData.filter(medicine => 
                medicine.id_supplier == selectedSupplierId
            );
            
            if (filteredObat.length === 0) {
                select.innerHTML = '<option value="">Tidak ada obat untuk supplier ini</option>';
                select.disabled = true;
                return;
            }
            
            filteredObat.forEach(function(medicine) {
                const option = document.createElement('option');
                option.value = medicine.id_obat;
                option.textContent = medicine.nama_obat + ' (' + (medicine.brand || 'No Brand') + ')';
                option.dataset.harga = medicine.harga_beli;
                option.dataset.supplier = medicine.supplier_name;
                select.appendChild(option);
            });
            
            // Restore selection if preserving data and value exists in filtered options
            if (!clearData && currentValue) {
                const optionExists = filteredObat.some(medicine => medicine.id_obat == currentValue);
                if (optionExists) {
                    select.value = currentValue;
                }
            }
        });
        
        // Only clear all row calculations when supplier changes (not when adding rows)
        if (clearData) {
            document.querySelectorAll('.obat-row').forEach(function(row) {
                row.querySelector('.harga-beli').value = '';
                row.querySelector('.jumlah').value = '';
                row.querySelector('.subtotal').value = '';
            });
            
            calculateTotal();
        }
    }
    
    // Function to populate a single obat dropdown
    function populateObatDropdown(select) {
        select.innerHTML = '';
        
        if (!selectedSupplierId) {
            select.innerHTML = '<option value="">Pilih Supplier Terlebih Dahulu</option>';
            select.disabled = true;
            return;
        }
        
        select.disabled = false;
        select.innerHTML = '<option value="">Pilih Obat</option>';
        
        const filteredObat = obatData.filter(medicine => 
            medicine.id_supplier == selectedSupplierId
        );
        
        if (filteredObat.length === 0) {
            select.innerHTML = '<option value="">Tidak ada obat untuk supplier ini</option>';
            select.disabled = true;
            return;
        }
        
        filteredObat.forEach(function(medicine) {
            const option = document.createElement('option');
            option.value = medicine.id_obat;
            option.textContent = medicine.nama_obat + ' (' + (medicine.brand || 'No Brand') + ')';
            option.dataset.harga = medicine.harga_beli;
            option.dataset.supplier = medicine.supplier_name;
            select.appendChild(option);
        });
    }
    
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
        
        // Only populate the new row's dropdown, don't touch existing rows
        const newRowSelect = newRow.querySelector('.obat-select');
        populateObatDropdown(newRowSelect);
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
            const hargaInput = row.querySelector('.harga-beli');
            const jumlahInput = row.querySelector('.jumlah');
            
            if (selectedOption.value) {
                hargaInput.value = selectedOption.dataset.harga;
                jumlahInput.value = 1;
            } else {
                hargaInput.value = '';
                jumlahInput.value = '';
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
        const harga = parseFloat(row.querySelector('.harga-beli').value) || 0;
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
    document.getElementById('pembelianForm').addEventListener('submit', function(e) {
        // Check if supplier is selected
        if (!selectedSupplierId) {
            e.preventDefault();
            alert('Pilih supplier terlebih dahulu!');
            return;
        }
        
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
            return;
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