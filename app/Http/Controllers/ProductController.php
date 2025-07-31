<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Obat::with(['supplier', 'spesifikasi']);

        // Filter pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        // Filter brand
        if ($request->has('brand') && $request->brand) {
            $query->where('brand', $request->brand);
        }

        // Filter harga
        if ($request->has('min_price') && $request->min_price) {
            $query->where('harga_jual', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('harga_jual', '<=', $request->max_price);
        }

        // Filter stok tersedia
        if ($request->has('available_only') && $request->available_only) {
            $query->where('stok', '>', 0);
        }

        // Filter obat yang tidak kadaluarsa
        if ($request->has('safe_only') && $request->safe_only) {
            $query->aman();
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'nama_obat');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if (in_array($sortBy, ['nama_obat', 'harga_jual', 'stok', 'kategori', 'brand'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate(12)->appends($request->query());
        
        // Data untuk filter dengan fallback
        $categories = Obat::distinct()->pluck('kategori')->filter()->sort()->values();
        $brands = Obat::distinct()->pluck('brand')->filter()->sort()->values();
        
        // Fallback jika tidak ada data
        if ($categories->isEmpty()) {
            $categories = collect(['Analgesik', 'Antibiotik', 'Vitamin', 'Antasida', 'Antihistamin']);
        }
        
        if ($brands->isEmpty()) {
            $brands = collect(['Sanbe', 'Kimia Farma', 'Kalbe', 'Dexa Medica', 'Indofarma']);
        }
        
        return view('landing.pages.products', compact('products', 'categories', 'brands'));
    }

    public function show($id)
    {
        $product = Obat::with(['supplier', 'spesifikasi'])->findOrFail($id);
        
        // Produk terkait (kategori sama, bukan produk yang sama)
        $relatedProducts = Obat::where('kategori', $product->kategori)
                              ->where('id_obat', '!=', $product->id_obat)
                              ->where('stok', '>', 0)
                              ->aman()
                              ->take(4)
                              ->get();
        
        return view('landing.components.product-detail', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json([]);
        }

        $products = Obat::where('nama_obat', 'like', "%{$query}%")
                       ->orWhere('kategori', 'like', "%{$query}%")
                       ->orWhere('brand', 'like', "%{$query}%")
                       ->where('stok', '>', 0)
                       ->aman()
                       ->select('id_obat', 'nama_obat', 'kategori', 'brand', 'harga_jual', 'image_url')
                       ->take(10)
                       ->get();

        return response()->json($products);
    }
}