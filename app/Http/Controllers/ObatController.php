<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ObatController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $obats = Obat::with('supplier')->orderBy('id_obat', 'desc')->get();
        } catch (\Exception $e) {
            Log::error('Error in ObatController@index: ' . $e->getMessage());
            $obats = collect(); // Empty collection as fallback
        }

        return view('admin.components.obat.obat', compact('obats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is superadmin
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return redirect()->route('obat.index')->with('error', 'Akses ditolak! Hanya superadmin yang dapat menambah obat.');
        }

        $suppliers = Supplier::all();
        return view('admin.components.obat.add-obat', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is superadmin
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return redirect()->route('obat.index')->with('error', 'Akses ditolak! Hanya superadmin yang dapat menambah obat.');
        }
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'brand' => 'nullable|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_kadaluarsa' => 'nullable|date|after:today',
            'id_supplier' => 'required|exists:suppliers,id_supplier',
        ], [
            'nama_obat.required' => 'Nama obat harus diisi',
            'kategori.required' => 'Kategori harus diisi',
            'satuan.required' => 'Satuan harus diisi',
            'harga_beli.required' => 'Harga beli harus diisi',
            'harga_jual.required' => 'Harga jual harus diisi',
            'image_url.image' => 'File harus berupa gambar',
            'image_url.mimes' => 'Format gambar harus: jpeg, png, jpg, gif',
            'image_url.max' => 'Ukuran gambar maksimal 2MB',
            'tanggal_kadaluarsa.date' => 'Format tanggal kadaluarsa tidak valid',
            'tanggal_kadaluarsa.after' => 'Tanggal kadaluarsa harus setelah hari ini',
            'id_supplier.required' => 'Supplier harus dipilih',
            'id_supplier.exists' => 'Supplier tidak valid',
        ]);

        try {
            // Handle file upload
            if ($request->hasFile('image_url')) {
                $image = $request->file('image_url');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('storage/obat'), $imageName);
                $validated['image_url'] = 'storage/obat/' . $imageName;
            }

            // Set initial stock to 0 - stock will be managed through purchases
            $validated['stok'] = 0;

            Obat::create($validated);

            return redirect()->route('obat.index')
                ->with('success', 'Obat berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan obat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $obat = Obat::with(['supplier', 'spesifikasi'])->findOrFail($id);
        $suppliers = Supplier::all();
        return view('admin.components.obat.show-obat', compact('obat', 'suppliers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Check if user is superadmin
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return redirect()->route('obat.show', $id)->with('error', 'Akses ditolak! Hanya superadmin yang dapat mengedit obat.');
        }

        $obat = Obat::findOrFail($id);
        $suppliers = Supplier::all();

        return view('admin.components.obat.edit', compact('obat', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if user is superadmin
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return redirect()->route('obat.show', $id)->with('error', 'Akses ditolak! Hanya superadmin yang dapat mengedit obat.');
        }

        $obat = Obat::findOrFail($id);

        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'brand' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'image_url' => 'nullable|string',
            'tanggal_kadaluarsa' => 'nullable|date|after:today',
            'id_supplier' => 'required|exists:suppliers,id_supplier',
        ], [
            'nama_obat.required' => 'Nama obat harus diisi',
            'kategori.required' => 'Kategori harus diisi',
            'brand.required' => 'Brand harus diisi',
            'satuan.required' => 'Satuan harus diisi',
            'harga_beli.required' => 'Harga beli harus diisi',
            'harga_jual.required' => 'Harga jual harus diisi',
            'stok.required' => 'Stok harus diisi',
            'tanggal_kadaluarsa.date' => 'Format tanggal kadaluarsa tidak valid',
            'tanggal_kadaluarsa.after' => 'Tanggal kadaluarsa harus setelah hari ini',
            'id_supplier.required' => 'Supplier harus dipilih',
            'id_supplier.exists' => 'Supplier tidak valid',
        ]);

        try {
            // Handle file upload
            if ($request->hasFile('image_url')) {
                // Delete old image if exists
                if ($obat->image_url && file_exists(public_path($obat->image_url))) {
                    unlink(public_path($obat->image_url));
                }

                $image = $request->file('image_url');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('storage/obat'), $imageName);
                $validated['image_url'] = 'storage/obat/' . $imageName;
            }

            $obat->update($validated);

            return redirect()->route('obat.show', $obat->id_obat)
                ->with('success', 'Obat berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui obat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Check if user is superadmin
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return redirect()->route('obat.show', $id)->with('error', 'Akses ditolak! Hanya superadmin yang dapat menghapus obat.');
        }

        try {
            $obat = Obat::findOrFail($id);

            // Check if obat is used in transactions
            if ($obat->penjualanDetail()->exists() || $obat->pembelianDetail()->exists()) {
                // For expired medicines, offer soft delete option
                if ($obat->isExpired()) {
                    // Set stock to 0 and add discontinued flag in description
                    $obat->update([
                        'stok' => 0,
                        'deskripsi' => ($obat->deskripsi ? $obat->deskripsi . ' | ' : '') . 'DIHENTIKAN - Obat kadaluarsa dan tidak dapat dijual (Dihapus: ' . now()->format('d/m/Y') . ')'
                    ]);

                    return redirect()->back()
                        ->with('success', 'Obat kadaluarsa berhasil dihentikan! Stok telah direset ke 0 dan obat ditandai sebagai tidak aktif. Data transaksi tetap terjaga.');
                } else {
                    return redirect()->back()
                        ->with('error', 'Tidak dapat menghapus obat yang sudah digunakan dalam transaksi! Gunakan fitur edit untuk mengubah status obat.');
                }
            }

            // If no transactions, proceed with normal deletion
            $obat->delete();

            return redirect()->route('obat.index')
                ->with('success', 'Obat berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus obat: ' . $e->getMessage());
        }
    }

    /**
     * Store spesifikasi for obat
     */
    public function storeSpesifikasi(Request $request, $id)
    {
        $obat = Obat::findOrFail($id);

        $validated = $request->validate([
            'kandungan' => 'nullable|string',
            'bentuk_sediaan' => 'nullable|string',
            'kemasan' => 'nullable|string',
            'cara_kerja' => 'nullable|string',
            'penyimpanan' => 'nullable|string',
        ]);

        try {
            if ($obat->spesifikasi) {
                $obat->spesifikasi->update($validated);
            } else {
                $obat->spesifikasi()->create(array_merge($validated, ['id_obat' => $obat->id_obat]));
            }

            return redirect()->route('obat.show', $obat->id_obat)
                ->with('success', 'Spesifikasi obat berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyimpan spesifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Display expired medicines list
     */
    public function listExpired()
    {
        try {
            // Get all expired medicines with suppliers
            $expiredObats = Obat::with('supplier')
                ->whereNotNull('tanggal_kadaluarsa')
                ->whereDate('tanggal_kadaluarsa', '<', now())
                ->orderBy('tanggal_kadaluarsa', 'asc') // Oldest expired first
                ->get();
        } catch (\Exception $e) {
            Log::error('Error in ObatController@listExpired: ' . $e->getMessage());
            $expiredObats = collect(); // Empty collection as fallback
        }

        return view('admin.components.obat.list-expired-obat', compact('expiredObats'));
    }

    /**
     * Force delete expired medicine (even with transaction history)
     */
    public function forceDeleteExpired(string $id)
    {
        // Check if user is superadmin
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya superadmin yang dapat menghapus obat.');
        }

        try {
            $obat = Obat::findOrFail($id);

            // Only allow force delete for expired medicines
            if (!$obat->isExpired()) {
                return redirect()->back()
                    ->with('error', 'Force delete hanya diperbolehkan untuk obat yang sudah kadaluarsa!');
            }

            // If medicine has transactions, give clear warning
            if ($obat->penjualanDetail()->exists() || $obat->pembelianDetail()->exists()) {
                // Instead of deleting, we set stock to 0 and mark as discontinued
                $obat->update([
                    'stok' => 0,
                    'deskripsi' => ($obat->deskripsi ? $obat->deskripsi . ' | ' : '') . 'DIHENTIKAN PAKSA - Obat kadaluarsa dihapus dari sistem (Tanggal: ' . now()->format('d/m/Y H:i') . ')'
                ]);

                Log::warning("Force delete attempted on expired medicine with transactions: {$obat->nama_obat} (ID: {$id})");

                return redirect()->back()
                    ->with('success', 'Obat kadaluarsa berhasil dihentikan secara paksa! Stok direset ke 0 dan obat ditandai tidak aktif. Data transaksi historis tetap terjaga untuk keperluan audit.');
            }

            // If no transactions, proceed with actual deletion
            $obat->delete();

            return redirect()->back()
                ->with('success', 'Obat kadaluarsa berhasil dihapus permanen!');
        } catch (\Exception $e) {
            Log::error('Error in ObatController@forceDeleteExpired: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus obat: ' . $e->getMessage());
        }
    }
}
