<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ApotekerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;

Route::get('/', function () {
    // Get sample products for homepage
    $products = \App\Models\Obat::with(['supplier', 'spesifikasi'])
        ->where('stok', '>', 0)
        ->aman() // Only non-expired products
        ->take(6) // Show only 6 products on homepage
        ->get();

    // Get categories and brands for filter
    $categories = \App\Models\Obat::distinct()->pluck('kategori')->filter()->sort()->values();
    $brands = \App\Models\Obat::distinct()->pluck('brand')->filter()->sort()->values();

    // Fallback jika tidak ada data
    if ($categories->isEmpty()) {
        $categories = collect(['Analgesik', 'Antibiotik', 'Vitamin', 'Antasida', 'Antihistamin']);
    }

    if ($brands->isEmpty()) {
        $brands = collect(['Sanbe', 'Kimia Farma', 'Kalbe', 'Dexa Medica', 'Indofarma']);
    }

    return view('welcome', compact('products', 'categories', 'brands'));
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/api/products/search', [ProductController::class, 'search'])->name('products.search');

// Authentication routes
Route::post('/login', [App\Http\Controllers\AuthController::class, 'userLogin'])->name('auth.login');
Route::get('/register', function () {
    return view('landing.pages.register-user');
})->name('user.register.form');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'userRegister'])->name('user.register');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'customerLogout'])->name('auth.logout');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'customerLogout'])->name('auth.logout.get');
Route::get('/user-info', [App\Http\Controllers\AuthController::class, 'getUserInfo'])->name('auth.user-info');

// Cart routes
Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [App\Http\Controllers\CartController::class, 'viewCart'])->name('cart.view');
Route::delete('/cart/{id}', [App\Http\Controllers\CartController::class, 'removeItem'])->name('cart.remove');
Route::put('/cart/{id}/quantity', [App\Http\Controllers\CartController::class, 'updateQuantity'])->name('cart.update-quantity');
Route::post('/cart/checkout', [App\Http\Controllers\CartController::class, 'store'])->name('cart.checkout');
Route::get('/orders', [App\Http\Controllers\CartController::class, 'customerOrders'])->name('customer.orders');



// Admin Authentication Routes
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login');

// Admin Logout Route
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::get('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout.get');

// Admin Obat Management Routes
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::resource('obat', ObatController::class);
});

// Test route without middleware
Route::get('/test-obat', [ObatController::class, 'index'])->name('test.obat');

// Admin routes - protected with admin middleware
Route::middleware(['admin'])->group(function () {

    // Super Admin Dashboard - hanya untuk superadmin
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard')
        ->middleware('superadmin');

    // Apoteker Dashboard - hanya untuk apoteker
    Route::get('/apoteker/dashboard', [ApotekerController::class, 'index'])
        ->name('apoteker.dashboard')
        ->middleware('apoteker');

    // Legacy dashboard route (redirect to appropriate dashboard)
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'superadmin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('apoteker.dashboard');
        }
    })->name('dashboard');

    // Obat routes - bisa diakses semua admin (apoteker & superadmin)
    Route::get('/obat', [ObatController::class, 'index'])->name('obat.index');
    Route::get('/obat/{id}', [ObatController::class, 'show'])->name('obat.show');
    Route::get('/obat-kadaluarsa', [ObatController::class, 'listExpired'])->name('list.obat.expired');
    Route::delete('/obat-kadaluarsa/{id}/force-delete', [ObatController::class, 'forceDeleteExpired'])->name('obat.forceDeleteExpired');

    // Admin Obat routes dengan prefix admin (untuk dashboard)
    Route::get('/admin/obat', [ObatController::class, 'index'])->name('admin.obat.index');
    Route::get('/admin/obat/{id}', [ObatController::class, 'show'])->name('admin.obat.show');
    Route::get('/admin/obat-kadaluarsa', [ObatController::class, 'listExpired'])->name('admin.list.obat.expired');
    Route::delete('/admin/obat-kadaluarsa/{id}/force-delete', [ObatController::class, 'forceDeleteExpired'])->name('admin.obat.forceDeleteExpired');

    // Spesifikasi - bisa diakses semua admin (apoteker & superadmin)
    Route::post('/obat/{id}/spesifikasi', [ObatController::class, 'storeSpesifikasi'])->name('obat.spesifikasi.store');
    Route::post('/admin/obat/{id}/spesifikasi', [ObatController::class, 'storeSpesifikasi'])->name('admin.obat.spesifikasi.store');

    // Penjualan routes - bisa diakses semua admin (apoteker & superadmin)
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::patch('/penjualan/{id}/status', [PenjualanController::class, 'updateStatus'])->name('penjualan.updateStatus');
    Route::get('/api/penjualan/stats', [PenjualanController::class, 'getStats'])->name('penjualan.stats');

    // Admin Penjualan routes dengan prefix admin (untuk dashboard)
    Route::get('/admin/penjualan', [PenjualanController::class, 'index'])->name('admin.penjualan.index');
    Route::get('/admin/penjualan/create', [PenjualanController::class, 'create'])->name('admin.penjualan.create');
    Route::post('/admin/penjualan', [PenjualanController::class, 'store'])->name('admin.penjualan.store');
    Route::get('/admin/penjualan/{id}', [PenjualanController::class, 'show'])->name('admin.penjualan.show');
    Route::patch('/admin/penjualan/{id}/status', [PenjualanController::class, 'updateStatus'])->name('admin.penjualan.updateStatus');
    Route::get('/admin/api/penjualan/stats', [PenjualanController::class, 'getStats'])->name('admin.penjualan.stats');



    // CRUD Obat - hanya superadmin
    Route::middleware(['superadmin'])->group(function () {
        Route::get('/obat/create', [ObatController::class, 'create'])->name('obat.create');
        Route::post('/obat', [ObatController::class, 'store'])->name('obat.store');
        Route::put('/obat/{id}', [ObatController::class, 'update'])->name('obat.update');
        Route::delete('/obat/{id}', [ObatController::class, 'destroy'])->name('obat.destroy');

        // Admin CRUD Obat routes dengan prefix admin (untuk dashboard)
        Route::get('/admin/obat/create', [ObatController::class, 'create'])->name('admin.obat.create');
        Route::post('/admin/obat', [ObatController::class, 'store'])->name('admin.obat.store');
        Route::put('/admin/obat/{id}', [ObatController::class, 'update'])->name('admin.obat.update');
        Route::delete('/admin/obat/{id}', [ObatController::class, 'destroy'])->name('admin.obat.destroy');
    });

    // Apoteker Management - hanya untuk superadmin
    Route::middleware(['admin', 'superadmin'])->group(function () {
        Route::get('/admin/apoteker', [ApotekerController::class, 'index'])->name('admin.apoteker.index');
        Route::get('/admin/apoteker/create', [ApotekerController::class, 'create'])->name('admin.apoteker.create');
        Route::post('/admin/apoteker', [ApotekerController::class, 'store'])->name('admin.apoteker.store');
        Route::get('/admin/apoteker/{id}', [ApotekerController::class, 'show'])->name('admin.apoteker.show');
        Route::get('/admin/apoteker/{id}/edit', [ApotekerController::class, 'edit'])->name('admin.apoteker.edit');
        Route::put('/admin/apoteker/{id}', [ApotekerController::class, 'update'])->name('admin.apoteker.update');
        Route::delete('/admin/apoteker/{id}', [ApotekerController::class, 'destroy'])->name('admin.apoteker.destroy');
    });

    // Apoteker Dashboard
    Route::get('/apoteker/dashboard', [ApotekerController::class, 'dashboard'])->name('apoteker.dashboard');

    // Super Admin only routes
    Route::middleware(['superadmin'])->group(function () {

        // Supplier Management
        Route::get('/admin/supplier', [SupplierController::class, 'index'])->name('admin.supplier');
        Route::get('/admin/supplier/create', [SupplierController::class, 'create'])->name('admin.supplier.create');
        Route::post('/admin/supplier', [SupplierController::class, 'store'])->name('admin.supplier.store');
        Route::get('/admin/supplier/{id}', [SupplierController::class, 'show'])->name('admin.supplier.show');
        Route::get('/admin/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('admin.supplier.edit');
        Route::put('/admin/supplier/{id}', [SupplierController::class, 'update'])->name('admin.supplier.update');
        Route::delete('/admin/supplier/{id}', [SupplierController::class, 'destroy'])->name('admin.supplier.destroy');

        // Pembelian Management - only superadmin can create/edit
        Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
        Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::post('/pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
        Route::get('/pembelian/{id}', [PembelianController::class, 'show'])->name('pembelian.show');
        Route::get('/api/pembelian/stats', [PembelianController::class, 'getStats'])->name('pembelian.stats');

        // Admin Pembelian routes dengan prefix admin (untuk dashboard)
        Route::get('/admin/pembelian', [PembelianController::class, 'index'])->name('admin.pembelian.index');
        Route::get('/admin/pembelian/create', [PembelianController::class, 'create'])->name('admin.pembelian.create');
        Route::post('/admin/pembelian', [PembelianController::class, 'store'])->name('admin.pembelian.store');
        Route::get('/admin/pembelian/{id}', [PembelianController::class, 'show'])->name('admin.pembelian.show');
        Route::get('/admin/api/pembelian/stats', [PembelianController::class, 'getStats'])->name('admin.pembelian.stats');

        // Pelanggan Management
        Route::get('/admin/pelanggan', [PelangganController::class, 'index'])->name('admin.pelanggan');
        Route::get('/admin/pelanggan/create', [PelangganController::class, 'create'])->name('admin.pelanggan.create');
        Route::post('/admin/pelanggan', [PelangganController::class, 'store'])->name('admin.pelanggan.store');
        Route::get('/admin/pelanggan/{id}', [PelangganController::class, 'show'])->name('admin.pelanggan.show');
        Route::get('/admin/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('admin.pelanggan.edit');
        Route::put('/admin/pelanggan/{id}', [PelangganController::class, 'update'])->name('admin.pelanggan.update');
        Route::delete('/admin/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('admin.pelanggan.destroy');

        // Laporan Management
        Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
        Route::get('/admin/laporan/show', [LaporanController::class, 'show'])->name('admin.laporan.show');
        Route::get('/admin/laporan/export', [LaporanController::class, 'export'])->name('admin.laporan.export');
    });
});

// Route aliases untuk kompatibilitas dashboard
Route::get('/admin/pelanggan', [PelangganController::class, 'index'])
    ->name('admin.pelanggan')
    ->middleware(['admin', 'superadmin']);
