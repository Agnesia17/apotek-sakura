<?php

namespace App\Http\Controllers;


use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pelanggans = Pelanggan::orderBy('created_at', 'desc')->get();
        return view('admin.components.pelanggan.pelanggan', compact('pelanggans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.components.pelanggan.add-pelanggan');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:pelanggan,username',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nama.required' => 'Nama pelanggan wajib diisi',
            'nama.max' => 'Nama pelanggan maksimal 255 karakter',
            'username.required' => 'Username wajib diisi',
            'username.max' => 'Username maksimal 255 karakter',
            'username.unique' => 'Username sudah digunakan',
            'telepon.required' => 'Telepon wajib diisi',
            'telepon.max' => 'Telepon maksimal 20 karakter',
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.max' => 'Alamat maksimal 500 karakter',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            Pelanggan::create($validated);
            return redirect()->route('admin.pelanggan')->with('success', 'Data pelanggan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan data pelanggan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $penjualanCount = $pelanggan->penjualan()->count();
        $totalBelanja = $pelanggan->penjualan()->sum('total_harga');

        return view('admin.components.pelanggan.show-pelanggan', compact('pelanggan', 'penjualanCount', 'totalBelanja'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('admin.components.pelanggan.edit-pelanggan', compact('pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:pelanggan,username,' . $id . ',id_pelanggan',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'nama.required' => 'Nama pelanggan wajib diisi',
            'nama.max' => 'Nama pelanggan maksimal 255 karakter',
            'username.required' => 'Username wajib diisi',
            'username.max' => 'Username maksimal 255 karakter',
            'username.unique' => 'Username sudah digunakan',
            'telepon.required' => 'Telepon wajib diisi',
            'telepon.max' => 'Telepon maksimal 20 karakter',
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.max' => 'Alamat maksimal 500 karakter',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            // Remove password from validated data if not provided
            if (empty($validated['password'])) {
                unset($validated['password']);
                unset($validated['password_confirmation']);
            }

            $pelanggan->update($validated);
            return redirect()->route('admin.pelanggan')->with('success', 'Data pelanggan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data pelanggan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);

            // Check if customer has related penjualan
            if ($pelanggan->penjualan()->count() > 0) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus pelanggan karena masih memiliki data penjualan terkait.');
            }

            $pelanggan->delete();
            return redirect()->route('admin.pelanggan')->with('success', 'Data pelanggan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data pelanggan: ' . $e->getMessage());
        }
    }
}
