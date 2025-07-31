<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Obat;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Check if user is super admin
            if (!auth()->user()->isSuperAdmin()) {
                return redirect()->route('apoteker.dashboard')->with('error', 'Akses ditolak! Anda tidak memiliki hak akses untuk dashboard Super Admin.');
            }

            // Date ranges
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            $thisMonth = Carbon::now()->startOfMonth();
            $lastMonth = Carbon::now()->subMonth()->startOfMonth();
            $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

            // Sales Statistics
            $salesStats = [
                'today' => [
                    'revenue' => Penjualan::whereDate('tanggal', $today)->sum('total_harga'),
                    'discount' => Penjualan::whereDate('tanggal', $today)->sum('diskon'),
                    'transactions' => Penjualan::whereDate('tanggal', $today)->count(),
                    'items_sold' => Penjualan::whereDate('tanggal', $today)
                        ->with('penjualanDetail')
                        ->get()
                        ->sum(function ($p) {
                            return $p->penjualanDetail->sum('jumlah');
                        })
                ],
                'yesterday' => [
                    'revenue' => Penjualan::whereDate('tanggal', $yesterday)->sum('total_harga'),
                    'transactions' => Penjualan::whereDate('tanggal', $yesterday)->count(),
                    'items_sold' => Penjualan::whereDate('tanggal', $yesterday)
                        ->with('penjualanDetail')
                        ->get()
                        ->sum(function ($p) {
                            return $p->penjualanDetail->sum('jumlah');
                        })
                ],
                'this_month' => [
                    'revenue' => Penjualan::whereDate('tanggal', '>=', $thisMonth)->sum('total_harga'),
                    'transactions' => Penjualan::whereDate('tanggal', '>=', $thisMonth)->count(),
                ]
            ];

            // Purchase Statistics
            $purchaseStats = [
                'today' => [
                    'cost' => Pembelian::whereDate('tanggal', $today)->sum('total_harga'),
                    'transactions' => Pembelian::whereDate('tanggal', $today)->count(),
                    'items_bought' => Pembelian::whereDate('tanggal', $today)
                        ->with('pembelianDetail')
                        ->get()
                        ->sum(function ($p) {
                            return $p->pembelianDetail->sum('jumlah');
                        })
                ],
                'yesterday' => [
                    'cost' => Pembelian::whereDate('tanggal', $yesterday)->sum('total_harga'),
                    'transactions' => Pembelian::whereDate('tanggal', $yesterday)->count(),
                ]
            ];

            // Calculate net revenue (sales - discount)
            $salesStats['today']['net_revenue'] = $salesStats['today']['revenue'] - $salesStats['today']['discount'];
            $salesStats['yesterday']['net_revenue'] = $salesStats['yesterday']['revenue'];

            // Recent Transactions (Last 10)
            $recentTransactions = Penjualan::with(['pelanggan', 'penjualanDetail'])
                ->orderBy('id_penjualan', 'desc')
                ->limit(10)
                ->get();

            // Top Selling Products (This month)
            $topProducts = Obat::with(['penjualanDetail' => function ($query) use ($thisMonth) {
                $query->whereHas('penjualan', function ($q) use ($thisMonth) {
                    $q->whereDate('tanggal', '>=', $thisMonth);
                });
            }])
                ->whereHas('penjualanDetail.penjualan', function ($q) use ($thisMonth) {
                    $q->whereDate('tanggal', '>=', $thisMonth);
                })
                ->withSum(['penjualanDetail as total_sold' => function ($query) use ($thisMonth) {
                    $query->whereHas('penjualan', function ($q) use ($thisMonth) {
                        $q->whereDate('tanggal', '>=', $thisMonth);
                    });
                }], 'jumlah')
                ->orderBy('total_sold', 'desc')
                ->limit(5)
                ->get();

            // General Stats
            $generalStats = [
                'total_obat' => Obat::count(),
                'total_pelanggan' => Pelanggan::count(),
                'obat_almost_expired' => Obat::whereDate('tanggal_kadaluarsa', '<=', Carbon::now()->addDays(30))
                    ->whereDate('tanggal_kadaluarsa', '>', Carbon::now())
                    ->count(),
                'obat_low_stock' => Obat::where('stok', '<=', 10)->count(),
            ];
        } catch (\Exception $e) {
            Log::error('Error in DashboardController@index: ' . $e->getMessage());

            // Fallback empty stats
            $salesStats = [
                'today' => ['revenue' => 0, 'discount' => 0, 'net_revenue' => 0, 'transactions' => 0, 'items_sold' => 0],
                'yesterday' => ['revenue' => 0, 'net_revenue' => 0, 'transactions' => 0, 'items_sold' => 0],
                'this_month' => ['revenue' => 0, 'transactions' => 0]
            ];
            $purchaseStats = [
                'today' => ['cost' => 0, 'transactions' => 0, 'items_bought' => 0],
                'yesterday' => ['cost' => 0, 'transactions' => 0]
            ];
            $recentTransactions = collect();
            $topProducts = collect();
            $generalStats = [
                'total_obat' => 0,
                'total_pelanggan' => 0,
                'obat_almost_expired' => 0,
                'obat_low_stock' => 0
            ];
        }

        // Prepare data for super admin dashboard
        $totalObat = $generalStats['total_obat'];
        $penjualanHariIni = $salesStats['today']['transactions'];
        $totalPelanggan = $generalStats['total_pelanggan'];
        $obatKadaluarsa = $generalStats['obat_almost_expired'];
        $recentActivities = collect(); // Placeholder for activities

        return view('admin.dashboard', compact(
            'totalObat',
            'penjualanHariIni',
            'totalPelanggan',
            'obatKadaluarsa',
            'recentActivities'
        ));
    }
}
