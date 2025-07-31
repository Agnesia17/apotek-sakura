@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail Penjualan #{{ $penjualan->id_penjualan }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary btn-sm">
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
                <h6 class="text-muted">Informasi Penjualan</h6>
                <table class="table table-borderless table-sm">
                    <tr>
                        <td style="width: 40%;" class="fw-bold">ID Penjualan:</td>
                        <td>#{{ $penjualan->id_penjualan }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal:</td>
                        <td>{{ $penjualan->tanggal ? $penjualan->tanggal->format('d F Y, H:i') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Kasir:</td>
                        <td>{{ auth()->user()->name ?? 'System' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">Informasi Pelanggan</h6>
                <table class="table table-borderless table-sm">
                    @if($penjualan->pelanggan)
                    <tr>
                        <td style="width: 40%;" class="fw-bold">Nama:</td>
                        <td>{{ $penjualan->pelanggan->nama }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Telepon:</td>
                        <td>{{ $penjualan->pelanggan->telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Alamat:</td>
                        <td>{{ $penjualan->pelanggan->alamat ?? '-' }}</td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="2" class="text-muted">Pelanggan Umum</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Detail Items -->
        <div class="row">
            <div class="col-12">
                <h6 class="text-muted mb-3">Detail Item Penjualan</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 40%;">Nama Obat</th>
                                <th style="width: 15%;">Brand</th>
                                <th style="width: 10%;">Satuan</th>
                                <th style="width: 10%;" class="text-center">Qty</th>
                                <th style="width: 10%;" class="text-end">Harga</th>
                                <th style="width: 10%;" class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualan->penjualanDetail as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-pill text-primary me-2"></i>
                                        <div>
                                            <div class="fw-bold">{{ $detail->obat->nama_obat ?? 'Obat tidak ditemukan' }}</div>
                                            @if($detail->obat)
                                                <small class="text-muted">{{ $detail->obat->kategori }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $detail->obat->brand ?? '-' }}</td>
                                <td>{{ $detail->obat->satuan ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">{{ $detail->jumlah }}</span>
                                </td>
                                <td class="text-end">
                                    @if($detail->obat)
                                        Rp {{ number_format($detail->obat->harga_jual, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end fw-bold">
                                    @if($detail->obat)
                                        Rp {{ number_format($detail->obat->harga_jual * $detail->jumlah, 0, ',', '.') }}
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
                            @if($penjualan->diskon > 0)
                            <tr>
                                <td>
                                    Diskon 
                                    <small class="text-muted">
                                        ({{ number_format(($penjualan->diskon / $subtotal) * 100, 1) }}%)
                                    </small>:
                                </td>
                                <td class="text-end text-success">-Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</td>
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
@endsection

@push('styles')
<style>
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
function printInvoice() {
    window.print();
}
</script>
@endpush