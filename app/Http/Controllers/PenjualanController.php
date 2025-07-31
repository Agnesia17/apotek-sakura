<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Pelanggan;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $penjualans = Penjualan::with(['pelanggan', 'penjualanDetail'])
                ->orderBy('id_penjualan', 'desc')
                ->get();

            // Calculate additional stats for the view
            $todayCount = Penjualan::whereDate('tanggal', today())->count();
            $totalOmzet = $penjualans->sum(function ($p) {
                return $p->total_harga - $p->diskon;
            });
            $totalItems = $penjualans->sum(function ($p) {
                return $p->penjualanDetail->sum('jumlah');
            });
        } catch (\Exception $e) {
            Log::error('Error in PenjualanController@index: ' . $e->getMessage());
            $penjualans = collect(); // Empty collection as fallback
            $todayCount = 0;
            $totalOmzet = 0;
            $totalItems = 0;
        }

        return view('admin.components.penjualan.penjualan', compact('penjualans', 'todayCount', 'totalOmzet', 'totalItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $pelanggan = Pelanggan::orderBy('nama')->get();
            $obat = Obat::where('stok', '>', 0)->orderBy('nama_obat')->get();

            return view('admin.components.penjualan.add-penjualan', compact('pelanggan', 'obat'));
        } catch (\Exception $e) {
            Log::error('Error in PenjualanController@create: ' . $e->getMessage());
            return redirect()->route('penjualan.index')->with('error', 'Gagal memuat form transaksi.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'diskon' => 'nullable|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'obat' => 'required|array|min:1',
            'obat.*.id_obat' => 'required|exists:obat,id_obat',
            'obat.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Create penjualan
            $penjualan = Penjualan::create([
                'tanggal' => $request->tanggal,
                'id_pelanggan' => $request->id_pelanggan,
                'total_harga' => $request->total_harga,
                'diskon' => $request->diskon ?? 0,
                'status' => 'selesai', // Direct sales get 'selesai' status
            ]);

            // Create penjualan details and update stock
            foreach ($request->obat as $item) {
                if (isset($item['id_obat']) && isset($item['jumlah']) && $item['id_obat'] && $item['jumlah']) {
                    $obat = Obat::findOrFail($item['id_obat']);

                    // Check stock availability
                    if ($obat->stok < $item['jumlah']) {
                        throw new \Exception("Stok obat {$obat->nama_obat} tidak mencukupi. Stok tersedia: {$obat->stok}");
                    }

                    // Create detail
                    PenjualanDetail::create([
                        'id_penjualan' => $penjualan->id_penjualan,
                        'id_obat' => $item['id_obat'],
                        'jumlah' => $item['jumlah'],
                    ]);

                    // Update stock
                    $obat->update([
                        'stok' => $obat->stok - $item['jumlah']
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('penjualan.index')->with('success', 'Transaksi penjualan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in PenjualanController@store: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $penjualan = Penjualan::with(['pelanggan', 'penjualanDetail.obat.supplier'])
                ->findOrFail($id);

            // Calculate subtotal and totals
            $subtotal = 0;
            foreach ($penjualan->penjualanDetail as $detail) {
                if ($detail->obat) {
                    $subtotal += $detail->obat->harga_jual * $detail->jumlah;
                }
            }

            $finalTotal = $subtotal - $penjualan->diskon;

            return view('admin.components.penjualan.detail-penjualan', compact('penjualan', 'subtotal', 'finalTotal'));
        } catch (\Exception $e) {
            Log::error('Error in PenjualanController@show: ' . $e->getMessage());
            return redirect()->route('penjualan.index')->with('error', 'Penjualan tidak ditemukan.');
        }
    }

    /**
     * Get penjualan statistics for dashboard
     */
    public function getStats()
    {
        try {
            $today = now()->startOfDay();
            $thisMonth = now()->startOfMonth();
            $thisYear = now()->startOfYear();

            $stats = [
                'today_sales' => Penjualan::whereDate('tanggal', $today)->sum('total_harga'),
                'this_month_sales' => Penjualan::whereDate('tanggal', '>=', $thisMonth)->sum('total_harga'),
                'this_year_sales' => Penjualan::whereDate('tanggal', '>=', $thisYear)->sum('total_harga'),
                'total_transactions' => Penjualan::count(),
                'today_transactions' => Penjualan::whereDate('tanggal', $today)->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Error in PenjualanController@getStats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get statistics'], 500);
        }
    }

    /**
     * Update penjualan status
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,selesai,dibatalkan'
        ]);

        try {
            $penjualan = Penjualan::findOrFail($id);
            $oldStatus = $penjualan->status;

            $penjualan->update([
                'status' => $request->status
            ]);

            Log::info("Penjualan status updated: ID {$id} from {$oldStatus} to {$request->status}");

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'status' => $penjualan->getStatusLabel(),
                'statusClass' => $penjualan->getStatusBadgeClass()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in PenjualanController@updateStatus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }
}
