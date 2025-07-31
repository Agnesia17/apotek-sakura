@extends('admin.layouts.app')

@section('content')
<div class="col-sm-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Pembelian #{{ $pembelian->id_pembelian }}</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('pembelian.index') }}" class="btn btn-secondary btn-sm">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
                <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                    <i class="ti ti-printer"></i> Print
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Header Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Informasi Pembelian</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td style="width: 40%;" class="fw-bold">ID Pembelian:</td>
                            <td>#{{ $pembelian->id_pembelian }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tanggal:</td>
                            <td>{{ $pembelian->tanggal ? $pembelian->tanggal->format('d F Y, H:i') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Informasi Supplier</h6>
                    <table class="table table-borderless table-sm">
                        @if($pembelian->supplier)
                        <tr>
                            <td style="width: 40%;" class="fw-bold">Nama:</td>
                            <td>{{ $pembelian->supplier->nama_supplier }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Telepon:</td>
                            <td>{{ $pembelian->supplier->telepon ?? '-' }}</td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="2" class="text-muted">Supplier tidak ditemukan</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Detail Items -->
            <div class="row">
                <div class="col-12">
                    <h6 class="text-muted mb-3">Detail Item Pembelian</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 40%;">Nama Obat</th>
                                    <th style="width: 15%;" class="text-center">Qty</th>
                                    <th style="width: 20%;" class="text-end">Harga Beli</th>
                                    <th style="width: 20%;" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pembelian->pembelianDetail as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="ti ti-pill text-muted"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $detail->obat->nama_obat ?? 'Obat tidak ditemukan' }}</h6>
                                                @if($detail->obat)
                                                    <small class="text-muted">{{ $detail->obat->brand ?? '-' }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light-info">{{ $detail->jumlah }}</span>
                                    </td>
                                    <td class="text-end">
                                        @if($detail->obat)
                                            Rp {{ number_format($detail->obat->harga_beli, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">
                                        @if($detail->obat)
                                            Rp {{ number_format($detail->obat->harga_beli * $detail->jumlah, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="row mt-4">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Ringkasan Pembayaran</h6>
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if($pembelian->diskon > 0)
                                <tr>
                                    <td>Diskon:</td>
                                    <td class="text-end text-success">-Rp {{ number_format($pembelian->diskon, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr class="border-top">
                                    <td class="fw-bold">Total Bayar:</td>
                                    <td class="text-end fw-bold text-primary h5">Rp {{ number_format($finalTotal, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.badge.bg-light-info {
    background-color: rgba(23, 162, 184, 0.1) !important;
    color: #17a2b8;
}

@media print {
    .btn, .page-header, .breadcrumb {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card-header {
        background: none !important;
        border: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Print functionality
function printInvoice() {
    window.print();
}
</script>
@endpush