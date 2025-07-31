@extends('landing.layout.app')

@section('title', 'Produk Obat - Apotek Sakura')

@section('content')
<style>
.product-item {
    position: relative;
    margin: 0 auto;
    max-width: 400px;
    height: 100%;
    transition: transform 0.3s ease;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    background-color: #fff;
}

.product-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

.product-item .product-link {
    position: relative;
    display: block;
    margin: 0 auto;
    cursor: pointer;
    text-decoration: none;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-image-container {
    position: relative;
    width: 100%;
    height: 300px;
    overflow: hidden;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
    padding: 0;
    background-color: #fff;
}

.product-item:hover .product-image {
    transform: scale(1.05);
}

.product-item .product-link .product-hover {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(25, 135, 84, 0.9);
    opacity: 0;
    transition: all ease 0.5s;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.product-item .product-link .product-hover:hover {
    opacity: 1;
}

.product-item .product-link .product-hover .product-hover-content {
    text-align: center;
    color: #fff;
}

.product-item .product-caption {
    background-color: #fff;
    text-align: center;
    padding: 20px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 200px;
}

.product-caption-heading {
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    color: #212529;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.product-caption-subheading {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.product-price {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0.5rem 0;
}

.product-stock {
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.product-brand {
    font-weight: 500;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.product-supplier {
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

.add-to-cart {
    transition: all 0.3s ease;
    margin-top: auto;
    width: 100%;
}

.add-to-cart:hover {
    transform: scale(1.02);
}

/* List View Styles */
.list-view .product-item-wrapper {
    flex: 0 0 100%;
    max-width: 100%;
}

.list-view .product-item {
    max-width: none;
    display: flex;
    flex-direction: row;
    align-items: stretch;
    margin-bottom: 20px;
    height: auto;
}

.list-view .product-link {
    flex: 0 0 200px;
    max-width: 200px;
    height: 200px;
}

.list-view .product-image-container {
    height: 250px;
}

.list-view .product-caption {
    flex: 1;
    text-align: left;
    padding: 20px;
    min-height: auto;
    justify-content: flex-start;
}

.list-view .product-caption-heading {
    -webkit-line-clamp: 1;
    font-size: 1.2rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .product-image-container {
        height: 250px;
    }
    
    .product-item .product-caption {
        padding: 15px;
        min-height: 180px;
    }
    
    .product-caption-heading {
        font-size: 1rem;
    }
    
    .list-view .product-item {
        flex-direction: column;
    }
    
    .list-view .product-link {
        flex: none;
        max-width: none;
        height: 200px;
    }
}

/* Filter Card Styling */
.card {
    border: none;
    border-radius: 15px;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.btn-success {
    background-color: #198754;
    border-color: #198754;
}

.btn-success:hover {
    background-color: #157347;
    border-color: #146c43;
}

/* Ensure equal height cards in grid */
.product-item-wrapper {
    display: flex;
    margin-bottom: 1.5rem;
}

.product-item-wrapper .product-item {
    width: 100%;
}
</style>

<section class="page-section bg-light" id="product" style="padding-top: 120px;">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase text-dark">Produk Obat</h2>
            <h3 class="section-subheading text-muted">Koleksi obat-obatan berkualitas untuk kesehatan Anda</h3>
        </div>

        <!-- Search and Filter Section -->
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('products.index') }}">
                            <div class="row">
                                <!-- Search -->
                                <div class="col-lg-4 mb-3">
                                    <label for="search" class="form-label">Cari Produk</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search" name="search" 
                                               placeholder="Nama obat, kategori, atau brand..." 
                                               value="{{ request('search') }}">
                                        <button class="btn btn-success" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Category Filter -->
                                <div class="col-lg-2 mb-3">
                                    <label for="kategori" class="form-label">Kategori</label>
                                    <select class="form-select" id="kategori" name="kategori">
                                        <option value="">Semua Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}" 
                                                    {{ request('kategori') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Brand Filter -->
                                <div class="col-lg-2 mb-3">
                                    <label for="brand" class="form-label">Brand</label>
                                    <select class="form-select" id="brand" name="brand">
                                        <option value="">Semua Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand }}" 
                                                    {{ request('brand') == $brand ? 'selected' : '' }}>
                                                {{ $brand }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Price Range -->
                                <div class="col-lg-2 mb-3">
                                    <label for="min_price" class="form-label">Harga Min</label>
                                    <input type="number" class="form-control" id="min_price" name="min_price" 
                                           placeholder="0" value="{{ request('min_price') }}">
                                </div>

                                <div class="col-lg-2 mb-3">
                                    <label for="max_price" class="form-label">Harga Max</label>
                                    <input type="number" class="form-control" id="max_price" name="max_price" 
                                           placeholder="999999" value="{{ request('max_price') }}">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Additional Filters -->
                                <div class="col-lg-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="available_only" 
                                               name="available_only" value="1" 
                                               {{ request('available_only') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="available_only">
                                            Hanya yang tersedia
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="safe_only" 
                                               name="safe_only" value="1" 
                                               {{ request('safe_only') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="safe_only">
                                            Hanya yang tidak kadaluarsa
                                        </label>
                                    </div>
                                </div>

                                <!-- Sort Options -->
                                <div class="col-lg-3 mb-3">
                                    <label for="sort_by" class="form-label">Urutkan berdasarkan</label>
                                    <select class="form-select" id="sort_by" name="sort_by">
                                        <option value="nama_obat" {{ request('sort_by') == 'nama_obat' ? 'selected' : '' }}>Nama</option>
                                        <option value="harga_jual" {{ request('sort_by') == 'harga_jual' ? 'selected' : '' }}>Harga</option>
                                        <option value="kategori" {{ request('sort_by') == 'kategori' ? 'selected' : '' }}>Kategori</option>
                                        <option value="stok" {{ request('sort_by') == 'stok' ? 'selected' : '' }}>Stok</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="sort_order" class="form-label">Urutan</label>
                                    <select class="form-select" id="sort_order" name="sort_order">
                                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>A-Z / Rendah-Tinggi</option>
                                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Z-A / Tinggi-Rendah</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success me-2">
                                        <i class="fas fa-filter"></i> Terapkan Filter
                                    </button>
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted mb-0">
                        Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
                        dari {{ $products->total() }} produk
                        @if(request('search'))
                            untuk pencarian "<strong>{{ request('search') }}</strong>"
                        @endif
                    </p>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-success btn-sm active" id="gridView">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" id="listView">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row" id="productsContainer">
            @forelse($products as $product)
            <div class="col-lg-4 col-md-6 mb-4 product-item-wrapper">
                <div class="product-item">
                    <a class="product-link" href="{{ route('products.show', $product->id_obat) }}">
                        <div class="product-image-container">
                            @if($product->image_url)
                                <img class="product-image" src="{{ asset($product->image_url) }}" alt="{{ $product->nama_obat }}" 
                                     onerror="this.src='{{ asset('landing/assets/default/obat.png') }}'" />
                            @else
                                <img class="product-image" src="{{ asset('landing/assets/default/obat.png') }}" alt="{{ $product->nama_obat }}" />
                            @endif
                            
                            <!-- Status Badge -->
                            @if($product->status_kadaluarsa == 'akan_kadaluarsa')
                                <span class="badge bg-warning position-absolute top-0 end-0 m-2">Akan Kadaluarsa</span>
                            @elseif($product->status_kadaluarsa == 'kadaluarsa')
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">Kadaluarsa</span>
                            @elseif($product->stok <= 10)
                                <span class="badge bg-warning position-absolute top-0 end-0 m-2">Stok Terbatas</span>
                            @endif
                            
                            <!-- Hover Overlay -->
                            <div class="product-hover">
                                <div class="product-hover-content">
                                    <i class="fas fa-eye fa-2x"></i>
                                    <p class="mt-2">Lihat Detail</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="product-caption">
                        <div class="product-caption-heading">{{ $product->nama_obat }}</div>
                        <div class="product-caption-subheading text-muted">{{ $product->kategori }}</div>
                        <div class="product-brand text-primary small mb-2">{{ $product->brand }}</div>
                        <div class="product-price text-success fw-bold">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</div>
                        <div class="product-stock small {{ $product->stok > 0 ? 'text-success' : 'text-danger' }}">
                            @if($product->stok > 0)
                                Stok: {{ $product->stok }} {{ $product->satuan }}
                            @else
                                Stok Habis
                            @endif
                        </div>
                        @if($product->supplier)
                            <div class="product-supplier text-muted small mt-1">{{ $product->supplier->nama_supplier }}</div>
                        @endif
                        
                        <!-- Add to Cart Button -->
                        @if($product->stok > 0 && $product->status_kadaluarsa != 'kadaluarsa')
                        <button class="btn btn-success btn-sm mt-3 add-to-cart" 
                                data-product-id="{{ $product->id_obat }}"
                                data-product-name="{{ $product->nama_obat }}"
                                data-product-price="{{ $product->harga_jual }}">
                            <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                        </button>
                        @else
                        <button class="btn btn-secondary btn-sm mt-3" disabled>
                            <i class="fas fa-ban"></i> Tidak Tersedia
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Tidak ada produk ditemukan</h4>
                    <p class="text-muted">Coba ubah kriteria pencarian atau filter Anda</p>
                    <a href="{{ route('products.index') }}" class="btn btn-success">
                        <i class="fas fa-refresh"></i> Lihat Semua Produk
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

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
    
    // View Toggle
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const container = document.getElementById('productsContainer');

    gridView.addEventListener('click', function() {
        container.classList.remove('list-view');
        gridView.classList.add('active');
        listView.classList.remove('active');
    });

    listView.addEventListener('click', function() {
        container.classList.add('list-view');
        listView.classList.add('active');
        gridView.classList.remove('active');
    });

    // Auto-submit on filter change
    const filterSelects = document.querySelectorAll('#kategori, #brand, #sort_by, #sort_order');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Auto-submit on checkbox change
    const filterCheckboxes = document.querySelectorAll('#available_only, #safe_only');
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Real-time search with debounce
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Auto-submit form when user stops typing for 800ms
                if (this.value.length >= 3 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 800);
        });
    }

    // Add to Cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const productPrice = this.dataset.productPrice;
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';
            this.disabled = true;
            
            // AJAX call to add to cart
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id_obat: productId,
                    jumlah: 1
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers.get('content-type'));
                
                // Always try to parse as JSON first, regardless of status code
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json().then(data => {
                        // If response is not ok, throw error with the JSON data
                        if (!response.ok) {
                            throw new Error(JSON.stringify(data));
                        }
                        return data;
                    });
                } else {
                    // If not JSON, handle as text
                    return response.text().then(text => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        throw new Error('Response is not JSON');
                    });
                }
            })
            .then(data => {
                // Restore button
                this.innerHTML = originalText;
                this.disabled = false;
                
                if (data.success) {
                    // Show success message
                    showNotification(data.message, 'success');
                    
                    // Update cart count if available
                    if (data.cart_count !== undefined) {
                        // Update cart count elements on this page
                        const cartCountElements = document.querySelectorAll('.cart-count');
                        cartCountElements.forEach(element => {
                            element.textContent = data.cart_count;
                            element.style.display = data.cart_count > 0 ? 'inline' : 'none';
                        });
                        
                        // Update cart count in navbar
                        if (window.updateCartCount) {
                            window.updateCartCount(data.cart_count);
                        }
                    }
                } else {
                    // Show error message
                    showNotification(data.message, 'error');
                    
                    // If not logged in, show login prompt
                    if (data.message.includes('login')) {
                        setTimeout(() => {
                            const loginModal = document.getElementById('loginModal');
                            if (loginModal) {
                                const modal = new bootstrap.Modal(loginModal);
                                modal.show();
                            }
                        }, 1500);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Restore button
                this.innerHTML = originalText;
                this.disabled = false;
                
                let errorMessage = 'Terjadi kesalahan saat menambahkan produk ke keranjang.';
                
                // Try to parse error message as JSON
                try {
                    const errorData = JSON.parse(error.message);
                    if (errorData.message) {
                        errorMessage = errorData.message;
                    }
                } catch (e) {
                    // If not JSON, handle as regular error
                    if (error.message.includes('HTTP error! status: 419')) {
                        errorMessage = 'Sesi telah berakhir. Silakan refresh halaman dan coba lagi.';
                    } else if (error.message.includes('Response is not JSON')) {
                        errorMessage = 'Server mengembalikan response yang tidak valid. Silakan coba lagi.';
                    } else if (error.message.includes('HTTP error! status: 401')) {
                        errorMessage = 'Anda harus login terlebih dahulu untuk menambahkan produk ke keranjang.';
                    }
                }
                
                showNotification(errorMessage, 'error');
                
                // If it's a login error, show login modal after a delay
                if (errorMessage.includes('login')) {
                    setTimeout(() => {
                        const loginModal = document.getElementById('loginModal');
                        if (loginModal) {
                            const modal = new bootstrap.Modal(loginModal);
                            modal.show();
                        }
                    }, 1500);
                }
            });
        });
    });

    // Price range auto-submit with debounce
    const priceInputs = document.querySelectorAll('#min_price, #max_price');
    let priceTimeout;
    
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(priceTimeout);
            priceTimeout = setTimeout(() => {
                this.form.submit();
            }, 1500);
        });
    });
});

// Notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    const alertClass = type === 'success' ? 'success' : (type === 'error' ? 'danger' : 'info');
    const iconClass = type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-circle' : 'info-circle');
    
    notification.className = `alert alert-${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    
    notification.innerHTML = `
        <i class="fas fa-${iconClass} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds (longer for error messages)
    const autoRemoveTime = type === 'error' ? 5000 : 3000;
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, autoRemoveTime);
}
</script>
@endsection