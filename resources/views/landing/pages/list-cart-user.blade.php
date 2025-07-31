@extends('landing.layout.app')

@section('title', 'Keranjang Belanja - Apotek Sakura')

@section('content')
<style>
    /* Navbar styling for cart page */
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
        .cart-container {
            background: #ffffff;
            min-height: 100vh;
            color: #333;
        }

        .cart-header {
            text-align: center;
            color: #28a745;
            margin-bottom: 40px;
            padding: 20px 0;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .cart-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: none;
        }

        .cart-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .cart-wrapper {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 40px;
            align-items: start;
        }

        .cart-items {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 25px;
            margin-bottom: 20px;
            background: #f8fafc;
            border-radius: 16px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .cart-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: #28a745;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .cart-item:hover {
            border-color: #28a745;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.15);
        }

        .cart-item:hover::before {
            opacity: 1;
        }

        .product-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }

        .product-image img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .product-details {
            flex: 1;
            min-width: 0;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .product-brand {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 8px;
        }

        .product-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #28a745;
            margin-bottom: 10px;
        }

        .stock-info {
            font-size: 0.8rem;
            color: #059669;
            background: #d1fae5;
            padding: 2px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        .quantity-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .quantity-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #475569;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            color: #28a745;
        }

        .qty-btn:hover {
            background: #28a745;
            color: white;
        }

        .qty-input {
            width: 50px;
            height: 40px;
            border: none;
            text-align: center;
            font-size: 1rem;
            font-weight: 600;
            background: #f8fafc;
            color: #1e293b;
        }

        .qty-input:focus {
            outline: none;
            background: white;
        }

        .remove-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 32px;
            height: 32px;
            border: none;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            opacity: 0;
            transform: scale(0.8);
        }

        .cart-item:hover .remove-btn {
            opacity: 1;
            transform: scale(1);
        }

        .remove-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        .cart-summary {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            position: sticky;
            top: 20px;
            height: fit-content;
        }

        .summary-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 25px;
            text-align: center;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-item:last-of-type {
            border-bottom: none;
        }

        .summary-label {
            font-size: 0.95rem;
            color: #64748b;
            font-weight: 500;
        }

        .summary-value {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .total-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f1f5f9;
        }

        .total-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }

        .total-label {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
        }

        .total-value {
            font-size: 1.4rem;
            font-weight: 800;
            color: #28a745;
        }

        .checkout-btn {
            width: 100%;
            background: #28a745;
            color: white;
            border: none;
            padding: 18px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 25px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .checkout-btn:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(40, 167, 69, 0.4);
        }

        .checkout-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .empty-cart {
            grid-column: 1 / -1;
            text-align: center;
            background: white;
            border-radius: 20px;
            padding: 80px 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .empty-icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 10px;
        }

        .empty-text {
            color: #64748b;
            margin-bottom: 30px;
        }

        .continue-shopping {
            background: #28a745;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .continue-shopping:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
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

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .cart-wrapper {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .cart-item {
                flex-direction: column;
                text-align: center;
                gap: 15px;
                padding: 20px;
            }

            .product-details {
                text-align: center;
            }

            .quantity-section {
                width: 100%;
            }

            .cart-header h1 {
                font-size: 2rem;
            }
        }
    </style>

    <div class="cart-container">
        <div class="container">
            <!-- Header -->
            <div class="cart-header">
                <h1><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h1>
                <p>Review produk yang akan Anda beli</p>
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

            @if(isset($cartItems) && count($cartItems) > 0)
                <div class="cart-wrapper" id="cartWrapper">
                    <!-- Cart Items -->
                    <div class="cart-items">
                        @foreach($cartItems as $item)
                            <div class="cart-item" data-item-id="{{ $item['id'] }}">
                                <button class="remove-btn" onclick="removeItem({{ $item['id'] }})">
                                    <i class="fas fa-times"></i>
                                </button>

                                <div class="product-image">
                                    @if($item['image'] && $item['image'] !== 'default.jpg')
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                    @else
                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='60' height='80' x='20' y='10' fill='%2328a745' rx='5'/%3E%3Crect width='40' height='60' x='30' y='20' fill='white' rx='3'/%3E%3Ctext x='50' y='45' text-anchor='middle' fill='%2328a745' font-size='12' font-weight='bold'%3E{{ substr($item['name'], 0, 4) }}%3C/text%3E%3C/svg%3E" alt="{{ $item['name'] }}">
                                    @endif
                                </div>

                                <div class="product-details">
                                    <h3 class="product-name">{{ $item['name'] }}</h3>
                                    <div class="product-brand">{{ $item['brand'] }}</div>
                                    <div class="product-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                                    <div class="stock-info">
                                        <i class="fas fa-check-circle"></i> Stok: {{ $item['stock'] }} tersedia
                                    </div>
                                </div>

                                <div class="quantity-section">
                                    <div class="quantity-label">Jumlah</div>
                                    <div class="quantity-controls">
                                        <button class="qty-btn" onclick="decreaseQuantity({{ $item['id'] }})">−</button>
                                        <input type="number" class="qty-input" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] }}" id="qty-{{ $item['id'] }}" onchange="updateQuantity({{ $item['id'] }}, this.value)">
                                        <button class="qty-btn" onclick="increaseQuantity({{ $item['id'] }})">+</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Cart Summary -->
                    <div class="cart-summary">
                        <h3 class="summary-title">
                            <i class="fas fa-receipt"></i> Ringkasan Pesanan
                        </h3>

                        @foreach($cartItems as $item)
                            <div class="summary-item" id="summary-{{ $item['id'] }}">
                                <span class="summary-label">{{ $item['name'] }}</span>
                                <span class="summary-value" id="summary-value-{{ $item['id'] }}">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach

                        <div class="total-section">
                            <div class="total-item">
                                <span class="total-label">TOTAL</span>
                                <span class="total-value" id="summary-total">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <form action="{{ route('cart.checkout') }}" method="POST" id="checkoutForm">
                            @csrf
                            <button type="submit" class="checkout-btn" id="checkoutBtn">
                                <i class="fas fa-credit-card"></i> Pesan Obat
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="empty-cart">
                    <div class="empty-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="empty-title">Keranjang Belanja Kosong</h3>
                    <p class="empty-text">Anda belum menambahkan produk apapun ke keranjang</p>
                    <a href="{{ route('products.index') }}" class="continue-shopping">
                        <i class="fas fa-arrow-left"></i>
                        Lanjutkan Belanja
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Set CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function removeItem(itemId) {
        if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
            const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
            
            if (itemElement) {
                itemElement.classList.add('loading');
                
                fetch(`/cart/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        itemElement.style.transform = 'translateX(100%)';
                        itemElement.style.opacity = '0';
                        
                        setTimeout(() => {
                            itemElement.remove();
                            const summaryElement = document.getElementById(`summary-${itemId}`);
                            if (summaryElement) summaryElement.remove();
                            
                            updateCartSummary();
                            
                            // Update cart count in navbar
                            if (data.cart_count !== undefined && window.updateCartCount) {
                                window.updateCartCount(data.cart_count);
                            }
                            
                            const remainingItems = document.querySelectorAll('.cart-item');
                            if (remainingItems.length === 0) {
                                location.reload(); // Reload to show empty cart
                            }
                        }, 300);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    itemElement.classList.remove('loading');
                    alert('Terjadi kesalahan saat menghapus item.');
                });
            }
        }
    }

    function increaseQuantity(itemId) {
        const qtyInput = document.getElementById(`qty-${itemId}`);
        const currentQty = parseInt(qtyInput.value);
        const maxQty = parseInt(qtyInput.max);
        
        if (currentQty < maxQty) {
            qtyInput.value = currentQty + 1;
            updateQuantity(itemId, qtyInput.value);
        } else {
            alert("Jumlah melebihi stok yang tersedia!");
        }
    }

    function decreaseQuantity(itemId) {
        const qtyInput = document.getElementById(`qty-${itemId}`);
        const currentQty = parseInt(qtyInput.value);
        
        if (currentQty > 1) {
            qtyInput.value = currentQty - 1;
            updateQuantity(itemId, qtyInput.value);
        }
    }

    function updateQuantity(itemId, newQuantity) {
        const quantity = parseInt(newQuantity);
        
        if (quantity > 0) {
            fetch(`/cart/${itemId}/quantity`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    jumlah: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    updateCartSummary();
                    
                    // Update cart count in navbar
                    if (data.cart_count !== undefined && window.updateCartCount) {
                        window.updateCartCount(data.cart_count);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui jumlah.');
            });
        }
    }

    function updateCartSummary() {
        let total = 0;

        document.querySelectorAll('.cart-item').forEach(item => {
            const itemId = item.getAttribute('data-item-id');
            const qtyInput = document.getElementById(`qty-${itemId}`);
            const priceElement = item.querySelector('.product-price');
            
            if (qtyInput && priceElement) {
                const quantity = parseInt(qtyInput.value);
                const price = parseInt(priceElement.textContent.replace(/[^\d]/g, ''));
                const subtotal = quantity * price;
                total += subtotal;

                const summaryValueEl = document.getElementById(`summary-value-${itemId}`);
                if (summaryValueEl) {
                    summaryValueEl.innerText = formatRupiah(subtotal);
                }
            }
        });

        const totalEl = document.getElementById('summary-total');
        if (totalEl) {
            totalEl.innerText = formatRupiah(total);
        }
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }

    // Input validation
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
        
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', function() {
                const max = parseInt(this.max);
                const min = parseInt(this.min || 1);
                let val = parseInt(this.value);

                if (isNaN(val)) {
                    this.value = min;
                    return;
                }

                if (val > max) this.value = max;
                if (val < min) this.value = min;
                
                const itemId = this.id.replace('qty-', '');
                updateQuantity(itemId, this.value);
            });
        });

        // Checkout form submission
        const checkoutForm = document.getElementById('checkoutForm');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                const checkoutBtn = document.getElementById('checkoutBtn');
                checkoutBtn.disabled = true;
                checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            });
        }
    });
</script>
@endsection