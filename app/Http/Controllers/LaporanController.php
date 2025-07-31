<?php

namespace App\Http\Controllers;


use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Display the laporan dashboard
     */
    public function index(): View
    {
        // Default filter: current month
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        return $this->getLaporanData('penjualan', $startDate, $endDate);
    }

    /**
     * Get laporan data based on type and date range
     */
    public function show(Request $request): View
    {
        $type = $request->get('type', 'penjualan'); // penjualan or pembelian
        $period = $request->get('period', 'month'); // today, week, month, year, custom
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Set date range based on period
        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $startDate = Carbon::parse($startDate)->startOfDay();
                    $endDate = Carbon::parse($endDate)->endOfDay();
                } else {
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                }
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
        }

        return $this->getLaporanData($type, $startDate, $endDate, $period);
    }

    /**
     * Get laporan data
     */
    private function getLaporanData($type, $startDate, $endDate, $period = 'month'): View
    {
        if ($type === 'penjualan') {
            // Get penjualan data
            $penjualans = Penjualan::with(['pelanggan', 'penjualanDetail.obat'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->get();

            $totalTransaksi = $penjualans->count();
            $totalNilai = $penjualans->sum('total_harga');
            $totalItem = $penjualans->sum(function ($penjualan) {
                return $penjualan->penjualanDetail->sum('jumlah');
            });

            // Group by date for chart data
            $chartData = $penjualans->groupBy(function ($penjualan) {
                return $penjualan->created_at ? $penjualan->created_at->format('Y-m-d') : 'Unknown';
            })->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total_harga')
                ];
            })->sortKeys();

            return view('admin.components.laporan.laporan', compact(
                'penjualans',
                'totalTransaksi',
                'totalNilai',
                'totalItem',
                'chartData',
                'type',
                'period',
                'startDate',
                'endDate'
            ));
        } else {
            // Get pembelian data
            $pembelians = Pembelian::with(['supplier', 'pembelianDetail.obat'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->get();

            $totalTransaksi = $pembelians->count();
            $totalNilai = $pembelians->sum('total_harga');
            $totalItem = $pembelians->sum(function ($pembelian) {
                return $pembelian->pembelianDetail->sum('jumlah');
            });

            // Group by date for chart data
            $chartData = $pembelians->groupBy(function ($pembelian) {
                return $pembelian->created_at ? $pembelian->created_at->format('Y-m-d') : 'Unknown';
            })->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total_harga')
                ];
            })->sortKeys();

            return view('admin.components.laporan.laporan', compact(
                'pembelians',
                'totalTransaksi',
                'totalNilai',
                'totalItem',
                'chartData',
                'type',
                'period',
                'startDate',
                'endDate'
            ));
        }
    }

    /**
     * Export laporan data
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'penjualan');
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Set date range (same logic as show method)
        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $startDate = Carbon::parse($startDate)->startOfDay();
                    $endDate = Carbon::parse($endDate)->endOfDay();
                } else {
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                }
                break;
        }

        // For now, return with success message
        // In the future, this can be implemented with Excel/PDF export
        return redirect()->back()->with('success', 'Fitur export akan segera tersedia!');
    }
}
