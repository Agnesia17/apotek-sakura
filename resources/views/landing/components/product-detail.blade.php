@extends('landing.layout.app')

@section('title', 'Detail Produk - ' . $product->nama_obat)

@section('content')
<style>
    /* Navbar styling for product detail page */
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
</style>

<section class="page-section" style="padding-top: 120px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->nama_obat }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Product Image -->
            <div class="col-lg-6 mb-4">
                <div class="position-relative">
                    @if($product->image_url)
                        <img class="img-fluid rounded shadow" src="{{ asset($product->image_url) }}" alt="{{ $product->nama_obat }}" 
                             onerror="this.src='{{ asset('landing/assets/img/portfolio/1.jpg') }}'" />
                    @else
                        <img class="img-fluid rounded shadow" src="{{ asset('landing/assets/img/portfolio/1.jpg') }}" alt="{{ $product->nama_obat }}" />
                    @endif
                    
                    <!-- Status Badge -->
                    @if($product->status_kadaluarsa == 'akan_kadaluarsa')
                        <span class="badge bg-warning position-absolute top-0 end-0 m-3 fs-6">Akan Kadaluarsa</span>
                    @elseif($product->status_kadaluarsa == 'kadaluarsa')
                        <span class="badge bg-danger position-absolute top-0 end-0 m-3 fs-6">Kadaluarsa</span>
                    @elseif($product->stok <= 10 && $product->stok > 0)
                        <span class="badge bg-warning position-absolute top-0 end-0 m-3 fs-6">Stok Terbatas</span>
                    @elseif($product->stok == 0)
                        <span class="badge bg-danger position-absolute top-0 end-0 m-3 fs-6">Stok Habis</span>
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-6">
                <div class="product-details">
                    <h1 class="text-uppercase mb-3 fw-bold">{{ $product->nama_obat }}</h1>
                    
                    <!-- Basic Info -->
                    <div class="product-info mb-4">
                        <div class="row mb-2">
                            <div class="col-4"><strong>Kategori:</strong></div>
                            <div class="col-8">
                                <span class="badge bg-primary">{{ $product->kategori }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Brand:</strong></div>
                            <div class="col-8 text-primary fw-semibold">{{ $product->brand }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Satuan:</strong></div>
                            <div class="col-8">{{ $product->satuan }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Harga:</strong></div>
                            <div class="col-8">
                                <span class="text-success fw-bold fs-4">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Stok:</strong></div>
                            <div class="col-8">
                                @if($product->stok > 0)
                                    <span class="text-success fw-semibold">{{ $product->stok }} {{ $product->satuan }} tersedia</span>
                                @else
                                    <span class="text-danger fw-semibold">Stok habis</span>
                                @endif
                            </div>
                        </div>
                        @if($product->tanggal_kadaluarsa)
                        <div class="row mb-2">
                            <div class="col-4"><strong>Exp. Date:</strong></div>
                            <div class="col-8">
                                <span class="{{ $product->status_kadaluarsa == 'kadaluarsa' ? 'text-danger' : ($product->status_kadaluarsa == 'akan_kadaluarsa' ? 'text-warning' : 'text-success') }}">
                                    {{ $product->tanggal_kadaluarsa ? $product->tanggal_kadaluarsa->format('d/m/Y') : 'Tidak ada data' }}
                                </span>
                            </div>
                        </div>
                        @endif
                        @if($product->supplier)
                        <div class="row mb-2">
                            <div class="col-4"><strong>Supplier:</strong></div>
                            <div class="col-8 text-muted">{{ $product->supplier->nama_supplier }}</div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Description -->
                    @if($product->deskripsi)
                    <div class="mb-4">
                        <h5 class="mb-3">Deskripsi Produk</h5>
                        <p class="text-muted">{{ $product->deskripsi }}</p>
                    </div>
                    @endif

                    <!-- Specifications -->
                    @if($product->spesifikasi)
                    <div class="mb-4">
                        <h5 class="mb-3">Spesifikasi</h5>
                        <div class="card">
                            <div class="card-body">
                                @if($product->spesifikasi->komposisi)
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Komposisi:</strong></div>
                                    <div class="col-8">{{ $product->spesifikasi->komposisi }}</div>
                                </div>
                                @endif
                                @if($product->spesifikasi->dosis)
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Dosis:</strong></div>
                                    <div class="col-8">{{ $product->spesifikasi->dosis }}</div>
                                </div>
                                @endif
                                @if($product->spesifikasi->indikasi)
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Indikasi:</strong></div>
                                    <div class="col-8">{{ $product->spesifikasi->indikasi }}</div>
                                </div>
                                @endif
                                @if($product->spesifikasi->kontraindikasi)
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Kontraindikasi:</strong></div>
                                    <div class="col-8">{{ $product->spesifikasi->kontraindikasi }}</div>
                                </div>
                                @endif
                                @if($product->spesifikasi->efek_samping)
                                <div class="row mb-2">
                                    <div class="col-4"><strong>Efek Samping:</strong></div>
                                    <div class="col-8">{{ $product->spesifikasi->efek_samping }}</div>
                                </div>
                                @endif
                                @if($product->spesifikasi->peringatan)
                                <div class="row mb-0">
                                    <div class="col-4"><strong>Peringatan:</strong></div>
                                    <div class="col-8 text-warning">{{ $product->spesifikasi->peringatan }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 mb-4">
                        @if($product->stok > 0 && $product->status_kadaluarsa != 'kadaluarsa')
                            <div class="d-flex align-items-center gap-2">
                                <label for="quantity" class="form-label mb-0">Jumlah:</label>
                                <input type="number" class="form-control" id="quantity" value="1" min="1" max="{{ $product->stok }}" style="width: 80px;">
                            </div>
                            <button class="btn btn-success btn-lg add-to-cart" 
                                    data-product-id="{{ $product->id_obat }}"
                                    data-product-name="{{ $product->nama_obat }}"
                                    data-product-price="{{ $product->harga_jual }}">
                                <i class="fas fa-shopping-cart me-2"></i>Tambah ke Keranjang
                            </button>
                        @else
                            <button class="btn btn-secondary btn-lg" disabled>
                                <i class="fas fa-ban me-2"></i>Tidak Tersedia
                            </button>
                        @endif
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-lg" id="backButton">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="text-center mb-4">Produk Terkait</h3>
                <div class="row">
                    @foreach($relatedProducts as $related)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <a href="{{ route('products.show', $related->id_obat) }}" class="text-decoration-none">
                                @if($related->image_url)
                                    <img class="card-img-top" src="{{ asset($related->image_url) }}" alt="{{ $related->nama_obat }}" 
                                         style="height: 200px; object-fit: cover;" 
                                         onerror="this.src='{{ asset('landing/assets/img/portfolio/1.jpg') }}'" />
                                @else
                                    <img class="card-img-top" src="{{ asset('landing/assets/img/portfolio/1.jpg') }}" alt="{{ $related->nama_obat }}" 
                                         style="height: 200px; object-fit: cover;" />
                                @endif
                                <div class="card-body text-center">
                                    <h6 class="card-title text-dark">{{ $related->nama_obat }}</h6>
                                    <p class="card-text text-muted small">{{ $related->kategori }}</p>
                                    <p class="card-text text-success fw-bold">Rp {{ number_format($related->harga_jual, 0, ',', '.') }}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<style>
.product-details .product-info {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid #198754;
}

.card {
    border: none;
    border-radius: 15px;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.badge {
    font-size: 0.9rem;
}

.breadcrumb {
    background: none;
    padding: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: #6c757d;
}
</style>

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
    
    // Add to Cart functionality
    const addToCartBtn = document.querySelector('.add-to-cart');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const productPrice = this.dataset.productPrice;
            const quantity = document.getElementById('quantity').value;
            
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
                    jumlah: parseInt(quantity)
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
                    // Show success message with quantity
                    showNotification(`${quantity} ${productName} berhasil ditambahkan ke keranjang!`, 'success');
                    
                    // Update cart count if available
                    const cartCountElements = document.querySelectorAll('.cart-count');
                    cartCountElements.forEach(element => {
                        if (data.cart_count !== undefined) {
                            element.textContent = data.cart_count;
                            element.style.display = data.cart_count > 0 ? 'inline' : 'none';
                        }
                    });
                    
                    // Reset quantity to 1
                    document.getElementById('quantity').value = 1;
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
    }
    
    // Quantity input validation
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            const value = parseInt(this.value);
            const max = parseInt(this.getAttribute('max'));
            const min = parseInt(this.getAttribute('min'));
            
            if (value > max) {
                this.value = max;
                showNotification(`Maksimal pembelian adalah ${max} item.`, 'warning');
            } else if (value < min) {
                this.value = min;
            }
        });
    }
});

// Notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    const alertClass = type === 'success' ? 'success' : (type === 'error' ? 'danger' : (type === 'warning' ? 'warning' : 'info'));
    const iconClass = type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-circle' : (type === 'warning' ? 'exclamation-triangle' : 'info-circle'));
    
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
    
    // Auto remove after specified time
    const autoRemoveTime = type === 'error' ? 5000 : (type === 'warning' ? 4000 : 3000);
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, autoRemoveTime);
}
</script>
@endsection