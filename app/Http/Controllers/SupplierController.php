<?php

namespace App\Http\Controllers;


use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')->get();
        return view('admin.components.supplier.supplier', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.components.supplier.add-supplier');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'kota' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi',
            'nama_supplier.max' => 'Nama supplier maksimal 255 karakter',
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.max' => 'Alamat maksimal 500 karakter',
            'kota.required' => 'Kota wajib diisi',
            'kota.max' => 'Kota maksimal 100 karakter',
            'telepon.required' => 'Telepon wajib diisi',
            'telepon.max' => 'Telepon maksimal 20 karakter',
        ]);

        try {
            Supplier::create($validated);
            return redirect()->route('admin.supplier')->with('success', 'Data supplier berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan data supplier: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $supplier = Supplier::findOrFail($id);
        $obatCount = $supplier->obat()->count();
        $pembelianCount = $supplier->pembelian()->count();

        return view('admin.components.supplier.show-supplier', compact('supplier', 'obatCount', 'pembelianCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.components.supplier.edit-supplier', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'kota' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi',
            'nama_supplier.max' => 'Nama supplier maksimal 255 karakter',
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.max' => 'Alamat maksimal 500 karakter',
            'kota.required' => 'Kota wajib diisi',
            'kota.max' => 'Kota maksimal 100 karakter',
            'telepon.required' => 'Telepon wajib diisi',
            'telepon.max' => 'Telepon maksimal 20 karakter',
        ]);

        try {
            $supplier->update($validated);
            return redirect()->route('admin.supplier')->with('success', 'Data supplier berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data supplier: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $supplier = Supplier::findOrFail($id);

            // Check if supplier has related obat or pembelian
            if ($supplier->obat()->count() > 0 || $supplier->pembelian()->count() > 0) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus supplier karena masih memiliki data obat atau pembelian terkait.');
            }

            $supplier->delete();
            return redirect()->route('admin.supplier')->with('success', 'Data supplier berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data supplier: ' . $e->getMessage());
        }
    }
}
