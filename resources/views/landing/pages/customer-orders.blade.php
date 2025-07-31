@extends('landing.layout.app')

@section('title', 'Riwayat Pesanan - Apotek Sakura')

@section('content')
<style>
    /* Navbar styling for orders page */
    .navbar {
        background-color: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    }
    
    .navbar-brand h2 {
        color: #28a745 !important;
    }
    
    .navbar-nav .nav-link {
        color: #333 !important;
        font-weight: 500;
    }
    
    .navbar-nav .nav-link:hover {
        color: #28a745 !important;
    }
    
    .navbar-toggler {
        border-color: #333;
    }
    
    .navbar-toggler:focus {
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    
    /* Ensure navbar stays consistent */
    .navbar.navbar-scrolled {
        background-color: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    }
    
    /* Override any existing navbar styles */
    .navbar-dark {
        background-color: rgba(255, 255, 255, 0.95) !important;
    }
    
    .navbar-dark .navbar-nav .nav-link {
        color: #333 !important;
    }
    
    /* Smooth scrolling for the entire page */
    html {
        scroll-behavior: smooth;
    }
    
    /* Ensure proper spacing for anchor links */
    section[id] {
        scroll-margin-top: 100px;
    }
</style>

<section class="page-section" style="padding-top: 120px;">
    <style>
        .orders-container {
            background: #f8f9fa;
            min-height: 100vh;
            color: #333;
        }

        .header {
            text-align: center;
            color: #28a745;
            margin-bottom: 40px;
            padding: 20px 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.8;
        }

        .orders-wrapper {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .order-item {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .order-item:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .order-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .order-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .order-number {
            font-size: 1.1rem;
            font-weight: 600;
            color: #28a745;
        }

        .order-date {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .order-total {
            font-size: 1.2rem;
            font-weight: 700;
            color: #28a745;
        }

        .order-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-diproses {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-selesai {
            background: #d4edda;
            color: #155724;
        }

        .status-dibatalkan {
            background: #f8d7da;
            color: #721c24;
        }

        .order-details {
            padding: 20px;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .product-image img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-size: 1rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 5px;
        }

        .product-details {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .product-quantity {
            font-size: 0.9rem;
            color: #28a745;
            font-weight: 600;
        }

        .empty-orders {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-icon {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .empty-text {
            margin-bottom: 30px;
        }

        .back-to-shop {
            background: #28a745;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .back-to-shop:hover {
            background: #218838;
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .product-item {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .product-info {
                text-align: center;
            }

            .header h1 {
                font-size: 2rem;
            }
        }
    </style>

    <div class="orders-container">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1><i class="fas fa-history"></i> Riwayat Pesanan</h1>
                <p>Lihat semua pesanan yang telah Anda buat</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <div class="orders-wrapper">
                @if(isset($orders) && count($orders) > 0)
                    @foreach($orders as $order)
                        <div class="order-item">
                            <div class="order-header">
                                <div class="order-info">
                                    <div class="order-number">{{ $order->no_invoice }}</div>
                                    <div class="order-date">{{ $order->tanggal ? $order->tanggal->format('d M Y H:i') : 'N/A' }}</div>
                                </div>
                                <div class="order-total">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                                <div class="order-status status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </div>
                            </div>
                            
                            <div class="order-details">
                                @foreach($order->penjualanDetail as $detail)
                                    <div class="product-item">
                                        <div class="product-image">
                                            @if($detail->obat->gambar && $detail->obat->gambar !== 'default.jpg')
                                                <img src="{{ asset('storage/' . $detail->obat->gambar) }}" alt="{{ $detail->obat->nama_obat }}">
                                            @else
                                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='40' height='60' x='30' y='20' fill='%2328a745' rx='3'/%3E%3Ctext x='50' y='45' text-anchor='middle' fill='%2328a745' font-size='8' font-weight='bold'%3E{{ substr($detail->obat->nama_obat, 0, 3) }}%3C/text%3E%3C/svg%3E" alt="{{ $detail->obat->nama_obat }}">
                                            @endif
                                        </div>
                                        
                                        <div class="product-info">
                                            <div class="product-name">{{ $detail->obat->nama_obat }}</div>
                                            <div class="product-details">{{ $detail->obat->merk ?? 'Generic' }} - Rp {{ number_format($detail->obat->harga_jual, 0, ',', '.') }}</div>
                                        </div>
                                        
                                        <div class="product-quantity">
                                            Qty: {{ $detail->jumlah }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-orders">
                        <div class="empty-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h3 class="empty-title">Belum Ada Pesanan</h3>
                        <p class="empty-text">Anda belum memiliki riwayat pesanan. Mulai belanja sekarang!</p>
                        <a href="{{ route('products.index') }}" class="back-to-shop">
                            <i class="fas fa-shopping-cart"></i>
                            Mulai Belanja
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure navbar styling is applied
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
            navbar.style.backdropFilter = 'blur(10px)';
            navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
            
            // Override any scroll-based styling
            navbar.classList.remove('navbar-scrolled');
            navbar.classList.add('navbar-scrolled');
        }
        
        // Prevent scroll-based navbar changes
        window.addEventListener('scroll', function() {
            if (navbar) {
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                navbar.style.backdropFilter = 'blur(10px)';
                navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
            }
        });
        
        // Handle navbar links for smooth scrolling and navigation
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                const linkText = this.textContent.trim();
                
                // Handle "Produk" link specifically
                if (linkText === 'Produk') {
                    e.preventDefault();
                    window.location.href = '{{ route("products.index") }}';
                    return;
                }
                
                // If it's an anchor link (starts with #), prevent default and scroll smoothly
                if (href && href.startsWith('#')) {
                    e.preventDefault();
                    const targetId = href.substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    } else {
                        // If target element not found, redirect to home page with anchor
                        window.location.href = '{{ url("/") }}' + href;
                    }
                }
            });
        });
    });
</script>
@endsection 