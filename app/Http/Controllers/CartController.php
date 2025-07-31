<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Obat;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            Log::info('addToCart called', [
                'request' => $request->all(),
                'wantsJson' => $request->wantsJson(),
                'ajax' => $request->ajax(),
                'headers' => $request->headers->all()
            ]);

            // Check if customer is logged in
            if (!Session::get('customer_logged_in')) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda harus login terlebih dahulu untuk menambahkan produk ke keranjang.'
                    ], 401, ['Content-Type' => 'application/json']);
                }
                return redirect()->back()->with('error', 'Anda harus login terlebih dahulu.');
            }

            // Validate request
            $request->validate([
                'id_obat' => 'required|exists:obat,id_obat',
                'jumlah' => 'required|integer|min:1'
            ]);

            $customerId = Session::get('customer_id');
            $productId = $request->id_obat;
            $quantity = $request->jumlah;

            // Get product information
            $obat = Obat::findOrFail($productId);

            // Check if product has sufficient stock
            if ($obat->stok < $quantity) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $obat->stok
                    ], 400, ['Content-Type' => 'application/json']);
                }
                return redirect()->back()->with('error', 'Stok tidak mencukupi.');
            }

            // Check if item already exists in cart
            $existingCartItem = Cart::where('id_pelanggan', $customerId)
                ->where('id_obat', $productId)
                ->first();

            if ($existingCartItem) {
                // Update quantity and subtotal
                $newQuantity = $existingCartItem->jumlah + $quantity;

                // Check total quantity against stock
                if ($obat->stok < $newQuantity) {
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Total jumlah melebihi stok yang tersedia. Stok tersedia: ' . $obat->stok . ', sudah ada di keranjang: ' . $existingCartItem->jumlah
                        ], 400, ['Content-Type' => 'application/json']);
                    }
                    return redirect()->back()->with('error', 'Total jumlah melebihi stok yang tersedia.');
                }

                $existingCartItem->update([
                    'jumlah' => $newQuantity
                ]);
            } else {
                // Create new cart item
                Cart::create([
                    'id_pelanggan' => $customerId,
                    'id_obat' => $productId,
                    'jumlah' => $quantity,
                    'tanggal' => now()
                ]);
            }

            // Get updated cart count
            $cartCount = Cart::getItemCountByPelanggan($customerId);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan ke keranjang.',
                    'cart_count' => $cartCount,
                    'product_name' => $obat->nama_obat
                ], 200, ['Content-Type' => 'application/json']);
            }

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid.',
                    'errors' => $e->errors()
                ], 422, ['Content-Type' => 'application/json']);
            }
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error adding to cart: ' . $e->getMessage());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menambahkan produk ke keranjang.'
                ], 500, ['Content-Type' => 'application/json']);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan produk ke keranjang.');
        }
    }

    public function viewCart()
    {
        // Pastikan user sudah login
        if (!Session::get('customer_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $idPelanggan = Session::get('customer_id');

        // Ambil item cart beserta relasi obat
        $cartItems = Cart::with('obat')
            ->where('id_pelanggan', $idPelanggan)
            ->get();

        // Ubah struktur data agar cocok dengan blade view kamu
        $formattedCartItems = $cartItems->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->obat->nama_obat ?? 'Tidak diketahui',
                'brand' => $item->obat->merk ?? '-',
                'price' => $item->obat->harga_jual ?? 0,
                'quantity' => $item->jumlah,
                'stock' => $item->obat->stok ?? 0,
                'image' => $item->obat->gambar ?? 'default.jpg',
            ];
        });

        // Hitung total
        $total = $formattedCartItems->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // Sales tax dan shipping (dummy)
        $salesTax = 0;
        $shipping = 0;

        return view('landing.pages.list-cart-user', [
            'cartItems' => $formattedCartItems,
            'total' => $total,
            'salesTax' => $salesTax,
            'shipping' => $shipping,
        ]);
    }
    public function removeItem($id): JsonResponse
    {
        if (!Session::get('customer_logged_in')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $idPelanggan = Session::get('customer_id');

        $cartItem = Cart::with('obat')->where('id', $id)
            ->where('id_pelanggan', $idPelanggan)
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        $productName = $cartItem->obat->nama_obat ?? 'Produk';
        $cartItem->delete();

        // Get updated cart count
        $cartCount = Cart::getItemCountByPelanggan($idPelanggan);

        return response()->json([
            'message' => $productName . ' berhasil dihapus dari keranjang',
            'removed_item_id' => $id,
            'cart_count' => $cartCount
        ]);
    }

    public function updateQuantity(Request $request, $id): JsonResponse
    {
        if (!Session::get('customer_logged_in')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::with('obat')->where('id', $id)
            ->where('id_pelanggan', Session::get('customer_id'))
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        // Check if quantity exceeds stock
        if ($request->jumlah > $cartItem->obat->stok) {
            return response()->json([
                'message' => 'Jumlah melebihi stok yang tersedia. Stok tersedia: ' . $cartItem->obat->stok
            ], 400);
        }

        $cartItem->jumlah = $request->jumlah;
        $cartItem->save();

        // Get updated cart count
        $cartCount = Cart::getItemCountByPelanggan(Session::get('customer_id'));

        return response()->json([
            'message' => 'Jumlah berhasil diperbarui',
            'new_quantity' => $request->jumlah,
            'subtotal' => $request->jumlah * $cartItem->obat->harga_jual,
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Process cart checkout and create penjualan
     */
    public function store(Request $request)
    {
        if (!Session::get('customer_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $idPelanggan = Session::get('customer_id');

        // Get cart items for this customer
        $cartItems = Cart::with('obat')
            ->where('id_pelanggan', $idPelanggan)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.view')->with('error', 'Keranjang kosong. Tambahkan produk terlebih dahulu.');
        }

        DB::beginTransaction();
        try {
            // Calculate total
            $totalHarga = 0;
            $stockErrors = [];

            // Validate stock availability first
            foreach ($cartItems as $cartItem) {
                if (!$cartItem->obat) {
                    throw new \Exception("Produk tidak ditemukan dalam keranjang.");
                }

                if ($cartItem->obat->stok < $cartItem->jumlah) {
                    $stockErrors[] = "Stok {$cartItem->obat->nama_obat} tidak mencukupi. Stok tersedia: {$cartItem->obat->stok}, diminta: {$cartItem->jumlah}";
                }

                $totalHarga += $cartItem->obat->harga_jual * $cartItem->jumlah;
            }

            // If there are stock errors, show them
            if (!empty($stockErrors)) {
                throw new \Exception("Stok tidak mencukupi:\n" . implode("\n", $stockErrors) . "\n\nSilakan kurangi jumlah pesanan atau hubungi apotek.");
            }

            // Create penjualan with status 'diproses'
            $penjualan = Penjualan::create([
                'tanggal' => Carbon::now(),
                'id_pelanggan' => $idPelanggan,
                'total_harga' => $totalHarga,
                'diskon' => 0,
                'status' => 'diproses', // Cart checkout gets 'diproses' status
            ]);

            // Create penjualan details and update stock
            foreach ($cartItems as $cartItem) {
                // Create detail
                PenjualanDetail::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_obat' => $cartItem->id_obat,
                    'jumlah' => $cartItem->jumlah,
                ]);

                // Update stock - REDUCE stock for sales
                $cartItem->obat->update([
                    'stok' => $cartItem->obat->stok - $cartItem->jumlah
                ]);
            }

            // Clear cart after successful checkout
            Cart::where('id_pelanggan', $idPelanggan)->delete();

            DB::commit();

            return redirect()->route('cart.view')->with('success', 'Pesanan berhasil dibuat! Silakan ambil pesanan obat di apotek dalam 1x24 jam. Jika melebihi waktu, pesanan akan diberikan kepada pelanggan lain jika stok menipis.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in CartController@store: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display customer's order history
     */
    public function customerOrders()
    {
        if (!Session::get('customer_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $idPelanggan = Session::get('customer_id');

        try {
            // Get customer orders with details
            $orders = Penjualan::with(['penjualanDetail.obat', 'pelanggan'])
                ->where('id_pelanggan', $idPelanggan)
                ->orderBy('tanggal', 'desc')
                ->get();

            return view('landing.pages.customer-orders', compact('orders'));
        } catch (\Exception $e) {
            Log::error('Error in CartController@customerOrders: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat riwayat pesanan.');
        }
    }
}
