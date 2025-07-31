<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ApotekerController extends Controller
{
    /**
     * Display a listing of apotekers.
     */
    public function index()
    {
        // Check if user is superadmin
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        try {
            $apotekers = User::where('role', 'apoteker')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin.components.apoteker.apoteker', compact('apotekers'));
        } catch (\Exception $e) {
            Log::error('Error in ApotekerController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat daftar apoteker.');
        }
    }

    /**
     * Display apoteker dashboard.
     */
    public function dashboard()
    {
        try {
            // Get statistics for apoteker dashboard
            $totalObat = \App\Models\Obat::count();
            $penjualanHariIni = \App\Models\Penjualan::whereDate('tanggal', today())->count();
            $stokMenipis = \App\Models\Obat::where('stok', '<=', 10)->where('stok', '>', 0)->count();
            $akanKadaluarsa = \App\Models\Obat::where('tanggal_kadaluarsa', '<=', now()->addMonths(3))
                ->where('tanggal_kadaluarsa', '>', now())
                ->count();

            // Get recent transactions
            $recentTransactions = \App\Models\Penjualan::with('pelanggan')
                ->orderBy('tanggal', 'desc')
                ->take(10)
                ->get();

            // Get stock alerts
            $stockAlerts = \App\Models\Obat::where('stok', '<=', 10)
                ->where('stok', '>', 0)
                ->orderBy('stok', 'asc')
                ->take(5)
                ->get();

            return view('admin.apoteker-dashboard', compact(
                'totalObat',
                'penjualanHariIni',
                'stokMenipis',
                'akanKadaluarsa',
                'recentTransactions',
                'stockAlerts'
            ));
        } catch (\Exception $e) {
            Log::error('Error in ApotekerController@dashboard: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat dashboard apoteker.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is superadmin
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('admin.components.apoteker.add-apoteker');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is superadmin
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'apoteker',
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            return redirect()->route('admin.apoteker.index')->with('success', 'Data apoteker berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error in ApotekerController@store: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data apoteker.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Check if user is superadmin
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        try {
            $apoteker = User::where('role', 'apoteker')->findOrFail($id);
            return view('admin.components.apoteker.show-apoteker', compact('apoteker'));
        } catch (\Exception $e) {
            Log::error('Error in ApotekerController@show: ' . $e->getMessage());
            return redirect()->route('admin.apoteker.index')->with('error', 'Data apoteker tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Check if user is superadmin
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        try {
            $apoteker = User::where('role', 'apoteker')->findOrFail($id);
            return view('admin.components.apoteker.edit-apoteker', compact('apoteker'));
        } catch (\Exception $e) {
            Log::error('Error in ApotekerController@edit: ' . $e->getMessage());
            return redirect()->route('admin.apoteker.index')->with('error', 'Data apoteker tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if user is superadmin
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        try {
            $apoteker = User::where('role', 'apoteker')->findOrFail($id);

            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $apoteker->update($data);

            return redirect()->route('admin.apoteker.index')->with('success', 'Data apoteker berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error in ApotekerController@update: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data apoteker.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Check if user is superadmin
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        try {
            $apoteker = User::where('role', 'apoteker')->findOrFail($id);
            $apoteker->delete();

            return redirect()->route('admin.apoteker.index')->with('success', 'Data apoteker berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error in ApotekerController@destroy: ' . $e->getMessage());
            return redirect()->route('admin.apoteker.index')->with('error', 'Gagal menghapus data apoteker.');
        }
    }
}
