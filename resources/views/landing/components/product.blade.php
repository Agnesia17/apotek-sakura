<section class="page-section bg-light" id="product">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase text-dark">Produk Obat</h2>
            <h3 class="section-subheading text-muted">Koleksi obat-obatan berkualitas untuk kesehatan Anda</h3>
        </div>

        <!-- Homepage Quick Search -->
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" placeholder="Cari obat yang Anda butuhkan..." id="homepageSearch">
                    <a href="{{ route('products.index') }}" class="btn btn-success">
                        <i class="fas fa-search me-2"></i>Lihat Semua Produk
                    </a>
                </div>
            </div>
        </div>

        <!-- Results Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted mb-0">
                        @if(method_exists($products, 'total'))
                            Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
                            dari {{ $products->total() }} produk
                        @else
                            Menampilkan {{ $products->count() }} produk terpilih
                        @endif
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
                        <div class="product-hover">
                            <div class="product-hover-content">
                                <i class="fas fa-eye fa-2x"></i>
                                <p class="mt-2">Lihat Detail</p>
                            </div>
                        </div>
                        @if($product->image_url)
                            <img class="img-fluid" src="{{ asset($product->image_url) }}" alt="{{ $product->nama_obat }}" 
                                 onerror="this.src='{{ asset('landing/assets/img/portfolio/1.jpg') }}'" />
                        @else
                            <img class="img-fluid" src="{{ asset('landing/assets/img/portfolio/1.jpg') }}" alt="{{ $product->nama_obat }}" />
                        @endif
                        
                        <!-- Status Badge -->
                        @if($product->status_kadaluarsa == 'akan_kadaluarsa')
                            <span class="badge bg-warning position-absolute top-0 end-0 m-2">Akan Kadaluarsa</span>
                        @elseif($product->status_kadaluarsa == 'kadaluarsa')
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">Kadaluarsa</span>
                        @elseif($product->stok <= 10)
                            <span class="badge bg-warning position-absolute top-0 end-0 m-2">Stok Terbatas</span>
                        @endif
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
        @if(method_exists($products, 'hasPages') && $products->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
        @elseif(!method_exists($products, 'hasPages') && $products->count() >= 6)
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="{{ route('products.index') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-eye me-2"></i>Lihat Semua Produk
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

<style>
.product-item {
    position: relative;
    margin: 0 auto;
    max-width: 400px;
    transition: transform 0.3s ease;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
}

.product-item .product-link .product-hover {
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(25, 135, 84, 0.9);
    opacity: 0;
    transition: all ease 0.5s;
    display: flex;
    align-items: center;
    justify-content: center;
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
    padding: 25px;
}

.product-caption-heading {
    font-weight: 700;
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
    color: #212529;
}

.product-price {
    font-size: 1.1rem;
    font-weight: 600;
}

.product-stock {
    font-size: 0.9rem;
}

.product-brand {
    font-weight: 500;
}

.add-to-cart {
    transition: all 0.3s ease;
}

.add-to-cart:hover {
    transform: scale(1.05);
}

/* List View Styles */
.list-view .product-item-wrapper {
    flex: 0 0 100%;
    max-width: 100%;
}

.list-view .product-item {
    max-width: none;
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.list-view .product-link {
    flex: 0 0 200px;
    max-width: 200px;
}

.list-view .product-caption {
    flex: 1;
    text-align: left;
    padding: 20px;
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // Homepage search functionality
    const homepageSearch = document.getElementById('homepageSearch');
    if (homepageSearch) {
        homepageSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    window.location.href = `{{ route('products.index') }}?search=${encodeURIComponent(searchTerm)}`;
                } else {
                    window.location.href = `{{ route('products.index') }}`;
                }
            }
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
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Response is not JSON');
                }
                return response.json();
            })
            .then(data => {
                // Restore button
                this.innerHTML = originalText;
                this.disabled = false;
                
                if (data.success) {
                    // Show success message
                    showNotification(data.message, 'success');
                    
                    // Update cart count if available
                    const cartCountElements = document.querySelectorAll('.cart-count');
                    cartCountElements.forEach(element => {
                        if (data.cart_count !== undefined) {
                            element.textContent = data.cart_count;
                            element.style.display = data.cart_count > 0 ? 'inline' : 'none';
                        }
                    });
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
                if (error.message.includes('HTTP error! status: 419')) {
                    errorMessage = 'Sesi telah berakhir. Silakan refresh halaman dan coba lagi.';
                } else if (error.message.includes('Response is not JSON')) {
                    errorMessage = 'Server mengembalikan response yang tidak valid. Silakan coba lagi.';
                }
                
                showNotification(errorMessage, 'error');
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