@extends('admin.layouts.app')

@section('title', 'Home')
@section('page-title', 'Home')

@push('styles')
<!-- [Page Specific CSS] start -->
@endpush

@section('content')
<!-- [ welcome message ] start -->
<div class="col-12 mb-4">
  <div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="ti ti-user-check me-2"></i>
    <strong>Selamat Datang, {{ auth()->user()->name }}!</strong>
    Anda login sebagai: <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
    @if(auth()->user()->isSuperAdmin())
      - Anda memiliki akses penuh ke semua fitur sistem.
    @else
      - Anda memiliki akses terbatas sesuai dengan peran Anda.
    @endif
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
<!-- [ welcome message ] end -->

<!-- [ Statistics Cards ] start -->
<div class="col-md-6 col-xl-3">
  <div class="card">
    <div class="card-body">
      <h6 class="mb-2 f-w-400 text-muted">Total Penjualan Hari Ini</h6>
      <h4 class="mb-3">Rp {{ number_format($salesStats['today']['net_revenue'], 0, ',', '.') }}
        @if($salesStats['today']['net_revenue'] > $salesStats['yesterday']['net_revenue'])
          <span class="badge bg-light-success border border-success">↑</span>
        @elseif($salesStats['today']['net_revenue'] < $salesStats['yesterday']['net_revenue'])
          <span class="badge bg-light-danger border border-danger">↓</span>
        @else
          <span class="badge bg-light-secondary border border-secondary">=</span>
        @endif
      </h4>
      <p class="mb-0 text-muted text-sm">
        Kemarin <span class="@if($salesStats['today']['net_revenue'] > $salesStats['yesterday']['net_revenue']) text-success @elseif($salesStats['today']['net_revenue'] < $salesStats['yesterday']['net_revenue']) text-danger @else text-secondary @endif">Rp {{ number_format($salesStats['yesterday']['net_revenue'], 0, ',', '.') }}</span>
        @if($salesStats['today']['net_revenue'] > $salesStats['yesterday']['net_revenue'])
          meningkat
        @elseif($salesStats['today']['net_revenue'] < $salesStats['yesterday']['net_revenue'])
          menurun
        @else
          sama
        @endif
      </p>
    </div>
  </div>
</div>
<div class="col-md-6 col-xl-3">
  <div class="card">
    <div class="card-body">
      <h6 class="mb-2 f-w-400 text-muted">Jumlah Obat Terjual Hari ini</h6>
      <h4 class="mb-3">{{ $salesStats['today']['items_sold'] }}
        @if($salesStats['today']['items_sold'] > $salesStats['yesterday']['items_sold'])
          <span class="badge bg-light-success border border-success">↑</span>
        @elseif($salesStats['today']['items_sold'] < $salesStats['yesterday']['items_sold'])
          <span class="badge bg-light-danger border border-danger">↓</span>
        @else
          <span class="badge bg-light-secondary border border-secondary">=</span>
        @endif
      </h4>
      <p class="mb-0 text-muted text-sm">
        Kemarin <span class="@if($salesStats['today']['items_sold'] > $salesStats['yesterday']['items_sold']) text-success @elseif($salesStats['today']['items_sold'] < $salesStats['yesterday']['items_sold']) text-danger @else text-secondary @endif">{{ $salesStats['yesterday']['items_sold'] }}</span>
        @if($salesStats['today']['items_sold'] > $salesStats['yesterday']['items_sold'])
          meningkat
        @elseif($salesStats['today']['items_sold'] < $salesStats['yesterday']['items_sold'])
          menurun
        @else
          sama
        @endif
      </p>
    </div>
  </div>
</div>
<div class="col-md-6 col-xl-3">
  <div class="card">
    <div class="card-body">
      <h6 class="mb-2 f-w-400 text-muted">Total Pembelian Hari ini</h6>
      <h4 class="mb-3">Rp {{ number_format($purchaseStats['today']['cost'], 0, ',', '.') }}
        @if($purchaseStats['today']['cost'] > $purchaseStats['yesterday']['cost'])
          <span class="badge bg-light-warning border border-warning">↑</span>
        @elseif($purchaseStats['today']['cost'] < $purchaseStats['yesterday']['cost'])
          <span class="badge bg-light-success border border-success">↓</span>
        @else
          <span class="badge bg-light-secondary border border-secondary">=</span>
        @endif
      </h4>
      <p class="mb-0 text-muted text-sm">
        Kemarin <span class="@if($purchaseStats['today']['cost'] < $purchaseStats['yesterday']['cost']) text-success @elseif($purchaseStats['today']['cost'] > $purchaseStats['yesterday']['cost']) text-warning @else text-secondary @endif">Rp {{ number_format($purchaseStats['yesterday']['cost'], 0, ',', '.') }}</span>
        @if($purchaseStats['today']['cost'] > $purchaseStats['yesterday']['cost'])
          meningkat
        @elseif($purchaseStats['today']['cost'] < $purchaseStats['yesterday']['cost'])
          menurun
        @else
          sama
        @endif
      </p>
    </div>
  </div>
</div>
<div class="col-md-6 col-xl-3">
  <div class="card">
    <div class="card-body">
      <h6 class="mb-2 f-w-400 text-muted">Jumlah Transaksi Hari ini</h6>
      <h4 class="mb-3">{{ $salesStats['today']['transactions'] }}
        @if($salesStats['today']['transactions'] > $salesStats['yesterday']['transactions'])
          <span class="badge bg-light-primary border border-primary">↑</span>
        @elseif($salesStats['today']['transactions'] < $salesStats['yesterday']['transactions'])
          <span class="badge bg-light-danger border border-danger">↓</span>
        @else
          <span class="badge bg-light-secondary border border-secondary">=</span>
        @endif
      </h4>
      <p class="mb-0 text-muted text-sm">
        Kemarin <span class="@if($salesStats['today']['transactions'] > $salesStats['yesterday']['transactions']) text-primary @elseif($salesStats['today']['transactions'] < $salesStats['yesterday']['transactions']) text-danger @else text-secondary @endif">{{ $salesStats['yesterday']['transactions'] }}</span>
        @if($salesStats['today']['transactions'] > $salesStats['yesterday']['transactions'])
          meningkat
        @elseif($salesStats['today']['transactions'] < $salesStats['yesterday']['transactions'])
          menurun
        @else
          sama
        @endif
      </p>
    </div>
  </div>
</div>

<div class="col-md-12 col-xl-8">
  <h5 class="mb-3">Transaksi Terbaru</h5>
  <div class="card tbl-card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-borderless mb-0">
          <thead>
            <tr>
              <th>NOTA</th>
              <th>TANGGAL</th>
              <th>NAMA PELANGGAN</th>
              <th>ITEM</th>
              <th class="text-end">TOTAL HARGA</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentTransactions as $transaction)
              <tr>
                <td><a href="{{ route('penjualan.show', $transaction->id_penjualan) }}" class="text-primary">#{{ $transaction->id_penjualan }}</a></td>
                <td>
                                                              <div>
                                                <strong>{{ $transaction->tanggal ? $transaction->tanggal->format('d/m/Y') : 'N/A' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $transaction->tanggal ? $transaction->tanggal->format('H:i') : 'N/A' }}</small>
                                            </div>
                </td>
                <td>
                  @if($transaction->pelanggan)
                    <div>
                      <h6 class="mb-0">{{ $transaction->pelanggan->nama }}</h6>
                      <small class="text-muted">{{ $transaction->pelanggan->telepon ?? '-' }}</small>
                    </div>
                  @else
                    <span class="text-muted">Pelanggan Umum</span>
                  @endif
                </td>
                <td>
                  <span class="badge bg-light-info">{{ $transaction->penjualanDetail->sum('jumlah') }} item</span>
                </td>
                <td class="text-end">
                  <strong>Rp {{ number_format($transaction->total_harga - $transaction->diskon, 0, ',', '.') }}</strong>
                  @if($transaction->diskon > 0)
                    <br>
                    <small class="text-success">Diskon: Rp {{ number_format($transaction->diskon, 0, ',', '.') }}</small>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-4">
                  <div class="text-muted">
                    <i class="ti ti-shopping-cart fs-1 d-block mb-2 opacity-50"></i>
                    <h6>Belum ada transaksi</h6>
                    <p class="mb-0">Transaksi akan muncul di sini setelah ada penjualan</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12 col-xl-4">
  <h5 class="mb-3">Obat Terlaris Bulan Ini</h5>
  <div class="card">
    <div class="list-group list-group-flush">
      @forelse($topProducts as $product)
        <div class="list-group-item">
          <div class="d-flex">
            <div class="flex-shrink-0">
              <div class="avtar avtar-s rounded-circle text-success bg-light-success">
                <i class="ti ti-pill f-18"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-1">{{ Str::limit($product->nama_obat, 20) }}</h6>
              <p class="mb-0 text-muted">{{ $product->supplier->nama ?? 'Supplier tidak diketahui' }}</p>
            </div>
            <div class="flex-shrink-0 text-end">
              <h6 class="mb-1">{{ $product->total_sold ?? 0 }}</h6>
              <p class="mb-0 text-muted">terjual</p>
            </div>
          </div>
        </div>
      @empty
        <div class="list-group-item text-center py-4">
          <div class="text-muted">
            <i class="ti ti-package fs-1 d-block mb-2 opacity-50"></i>
            <h6>Belum ada data</h6>
            <p class="mb-0">Data penjualan bulan ini belum tersedia</p>
          </div>
        </div>
      @endforelse
    </div>
    
    <!-- Additional Stats -->
    <div class="card-footer">
      <div class="row text-center">
        <div class="col-6">
          <h5 class="mb-0">{{ $generalStats['total_obat'] }}</h5>
          <p class="text-muted mb-0">Total Obat</p>
        </div>
        <div class="col-6">
          <h5 class="mb-0 text-warning">{{ $generalStats['obat_low_stock'] }}</h5>
          <p class="text-muted mb-0">Stok Rendah</p>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<!-- [ Additional Dashboard Stats ] end -->
@endsection

@push('page-scripts')
<!-- [Page Specific JS] start -->
<script src="{{ asset('assets/admin/js/plugins/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/pages/dashboard-default.js') }}"></script>
<!-- [Page Specific JS] end -->
@endpush