<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function adminLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by username and check if admin role
        $user = User::where('username', $request->username)
            ->whereIn('role', ['superadmin', 'apoteker'])
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Login the admin user
            Auth::login($user);

            // Set additional session data for admin
            Session::put('admin_logged_in', true);
            Session::put('admin_user_id', $user->id);
            Session::put('admin_role', $user->role);
            Session::put('admin_name', $user->name);

            // Redirect based on role
            if ($user->role === 'superadmin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login Super Admin berhasil! Selamat datang ' . $user->name);
            } else {
                return redirect()->route('apoteker.dashboard')->with('success', 'Login Apoteker berhasil! Selamat datang ' . $user->name);
            }
        }

        return redirect()->back()->with('error', 'Username atau password admin salah!');
    }

    public function userLogin(Request $request)
    {
        // Use username for both customer and admin login
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        // First, try to find customer by username in Pelanggan table
        $pelanggan = Pelanggan::where('username', $request->username)->first();

        if ($pelanggan && Hash::check($request->password, $pelanggan->password)) {
            // Customer login successful
            Session::put('customer_logged_in', true);
            Session::put('customer_id', $pelanggan->id_pelanggan);
            Session::put('customer_name', $pelanggan->nama);
            Session::put('customer_username', $pelanggan->username);

            if ($request->ajax()) {
                try {
                    $cartCount = $this->getCartCount($pelanggan->id_pelanggan);

                    return response()->json([
                        'success' => true,
                        'message' => 'Login berhasil! Selamat datang ' . $pelanggan->nama,
                        'user' => [
                            'name' => $pelanggan->nama,
                            'username' => $pelanggan->username,
                            'cart_count' => $cartCount
                        ]
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error in AJAX login response: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Login berhasil tapi terjadi kesalahan: ' . $e->getMessage()
                    ], 500);
                }
            }

            return redirect()->route('products.index')->with('success', 'Login berhasil! Selamat datang ' . $pelanggan->nama);
        }

        // If not customer, try to find admin by username in User table
        $user = User::where('username', $request->username)
            ->whereIn('role', ['superadmin', 'apoteker'])
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Admin login successful
            Auth::login($user);
            Session::put('admin_logged_in', true);
            Session::put('admin_user_id', $user->id);
            Session::put('admin_role', $user->role);
            Session::put('admin_name', $user->name);

            if ($request->ajax()) {
                // Redirect based on role
                $redirectUrl = $user->role === 'superadmin' ? route('admin.dashboard') : route('apoteker.dashboard');

                return response()->json([
                    'success' => true,
                    'message' => 'Login ' . ucfirst($user->role) . ' berhasil! Selamat datang ' . $user->name,
                    'redirect' => $redirectUrl
                ]);
            }

            // Redirect based on role
            if ($user->role === 'superadmin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login Super Admin berhasil! Selamat datang ' . $user->name);
            } else {
                return redirect()->route('apoteker.dashboard')->with('success', 'Login Apoteker berhasil! Selamat datang ' . $user->name);
            }
        }

        // Login failed
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah!'
            ], 401);
        }

        return redirect()->back()->with('error', 'Username atau password salah!');
    }

    public function userRegister(Request $request)
    {
        try {
            // Validation dengan nama field yang sesuai dengan form
            $request->validate([
                'nama_pelanggan' => 'required|string|max:255',
                'username' => 'required|string|unique:pelanggan,username|max:255',
                'telpon' => 'required|string|max:20',
                'kota' => 'required|string|max:100',
                'alamat' => 'required|string|max:500',
                'password' => 'required|string|min:6|confirmed',
            ], [
                'nama_pelanggan.required' => 'Nama lengkap harus diisi',
                'username.required' => 'Username harus diisi',
                'username.unique' => 'Username sudah digunakan, pilih username lain',
                'telpon.required' => 'Nomor telepon harus diisi',
                'kota.required' => 'Kota harus diisi',
                'alamat.required' => 'Alamat harus diisi',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 6 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
            ]);

            // Create pelanggan dengan field yang benar
            $pelanggan = Pelanggan::create([
                'nama' => $request->nama_pelanggan,
                'username' => $request->username,
                'telepon' => $request->telpon,
                'alamat' => $request->alamat . ', ' . $request->kota,
                'password' => Hash::make($request->password),
            ]);

            // Set session for customer authentication
            Session::put('customer_logged_in', true);
            Session::put('customer_id', $pelanggan->id_pelanggan);
            Session::put('customer_name', $pelanggan->nama);
            Session::put('customer_username', $pelanggan->username);

            return redirect()->route('home')->with('success', 'Registrasi berhasil! Selamat datang ' . $pelanggan->nama);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::forget('admin_logged_in');
        Session::forget('admin_user_id');
        Session::forget('admin_role');
        Session::forget('customer_logged_in');
        Session::forget('customer_id');
        Session::forget('customer_name');
        Session::forget('customer_username');
        Session::invalidate();
        Session::regenerateToken();

        return redirect()->route('home')->with('success', 'Logout berhasil!');
    }

    public function customerLogout(Request $request)
    {
        Session::forget('customer_logged_in');
        Session::forget('customer_id');
        Session::forget('customer_name');
        Session::forget('customer_username');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil!'
            ]);
        }

        return redirect('/')->with('success', 'Logout berhasil!');
    }

    /**
     * Get user info for AJAX calls
     */
    public function getUserInfo()
    {
        if (Session::get('customer_logged_in')) {
            $cartCount = $this->getCartCount(Session::get('customer_id'));

            return response()->json([
                'logged_in' => true,
                'user' => [
                    'name' => Session::get('customer_name'),
                    'username' => Session::get('customer_username'),
                    'cart_count' => $cartCount,
                    'type' => 'customer'
                ]
            ]);
        }

        return response()->json(['logged_in' => false]);
    }

    /**
     * Get cart count for pelanggan
     */
    private function getCartCount($pelangganId)
    {
        try {
            return \App\Models\Cart::getItemCountByPelanggan($pelangganId) ?? 0;
        } catch (\Exception $e) {
            Log::error('Error getting cart count: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Check if customer is logged in
     */
    public static function isCustomerLoggedIn()
    {
        return Session::get('customer_logged_in', false);
    }

    /**
     * Get current customer ID
     */
    public static function getCustomerId()
    {
        return Session::get('customer_id');
    }
}
