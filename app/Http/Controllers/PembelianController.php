<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Supplier;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $pembelians = Pembelian::with(['supplier', 'pembelianDetail'])
                ->orderBy('id_pembelian', 'desc')
                ->get();

            // Calculate additional stats for the view
            $todayCount = Pembelian::whereDate('tanggal', today())->count();
            $totalPembelian = $pembelians->sum(function ($p) {
                return $p->total_harga - $p->diskon;
            });
            $totalItems = $pembelians->sum(function ($p) {
                return $p->pembelianDetail->sum('jumlah');
            });
        } catch (\Exception $e) {
            Log::error('Error in PembelianController@index: ' . $e->getMessage());
            $pembelians = collect(); // Empty collection as fallback
            $todayCount = 0;
            $totalPembelian = 0;
            $totalItems = 0;
        }

        return view('admin.components.pembelian.pembelian', compact('pembelians', 'todayCount', 'totalPembelian', 'totalItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $suppliers = Supplier::orderBy('nama_supplier')->get();
            $obat = Obat::with('supplier')->orderBy('nama_obat')->get();

            // Pass obat as JSON for JavaScript filtering
            $obatJson = $obat->map(function ($medicine) {
                return [
                    'id_obat' => $medicine->id_obat,
                    'nama_obat' => $medicine->nama_obat,
                    'brand' => $medicine->brand,
                    'harga_beli' => $medicine->harga_beli,
                    'id_supplier' => $medicine->id_supplier,
                    'supplier_name' => $medicine->supplier->nama_supplier ?? 'Tidak ada'
                ];
            });

            return view('admin.components.pembelian.add-pembelian', compact('suppliers', 'obat', 'obatJson'));
        } catch (\Exception $e) {
            Log::error('Error in PembelianController@create: ' . $e->getMessage());
            return redirect()->route('pembelian.index')->with('error', 'Gagal memuat form pembelian.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log user info for debugging
        Log::info('Pembelian store accessed by user:', [
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->role ?? 'not authenticated',
            'user_name' => Auth::user()->name ?? 'not authenticated'
        ]);

        $request->validate([
            'tanggal' => 'required|date',
            'id_supplier' => 'required|exists:suppliers,id_supplier',
            'diskon' => 'nullable|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'obat' => 'required|array|min:1',
            'obat.*.id_obat' => 'required|exists:obat,id_obat',
            'obat.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Log request data for debugging
            Log::info('Pembelian store request data:', [
                'tanggal' => $request->tanggal,
                'id_supplier' => $request->id_supplier,
                'total_harga' => $request->total_harga,
                'diskon' => $request->diskon,
                'obat' => $request->obat
            ]);

            // Create pembelian
            $pembelian = Pembelian::create([
                'tanggal' => $request->tanggal,
                'id_supplier' => $request->id_supplier,
                'total_harga' => $request->total_harga,
                'diskon' => $request->diskon ?? 0,
            ]);

            Log::info('Pembelian created:', ['id' => $pembelian->id_pembelian]);

            // Create pembelian details and update stock
            foreach ($request->obat as $index => $item) {
                Log::info("Processing obat item {$index}:", $item);

                if (isset($item['id_obat']) && isset($item['jumlah']) && $item['id_obat'] && $item['jumlah']) {
                    $obat = Obat::findOrFail($item['id_obat']);

                    Log::info("Found obat:", [
                        'id_obat' => $obat->id_obat,
                        'nama_obat' => $obat->nama_obat,
                        'current_stok' => $obat->stok,
                        'adding_stok' => $item['jumlah']
                    ]);

                    // Create detail
                    $detail = PembelianDetail::create([
                        'id_pembelian' => $pembelian->id_pembelian,
                        'id_obat' => $item['id_obat'],
                        'jumlah' => $item['jumlah'],
                    ]);

                    Log::info("PembelianDetail created:", [
                        'id_pembelian' => $detail->id_pembelian,
                        'id_obat' => $detail->id_obat,
                        'jumlah' => $detail->jumlah
                    ]);

                    // Update stock - ADD stock for purchases
                    $oldStok = $obat->stok;
                    $newStok = $oldStok + $item['jumlah'];

                    $obat->update([
                        'stok' => $newStok
                    ]);

                    Log::info("Stock updated:", [
                        'obat_id' => $obat->id_obat,
                        'old_stok' => $oldStok,
                        'new_stok' => $newStok
                    ]);
                } else {
                    Log::warning("Skipping invalid obat item:", $item);
                }
            }

            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'Transaksi pembelian berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in PembelianController@store: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $pembelian = Pembelian::with(['supplier', 'pembelianDetail.obat'])
                ->findOrFail($id);

            // Calculate subtotal and totals
            $subtotal = 0;
            foreach ($pembelian->pembelianDetail as $detail) {
                if ($detail->obat) {
                    $subtotal += $detail->obat->harga_beli * $detail->jumlah;
                }
            }

            $finalTotal = $subtotal - $pembelian->diskon;

            return view('admin.components.pembelian.detail-pembelian', compact('pembelian', 'subtotal', 'finalTotal'));
        } catch (\Exception $e) {
            Log::error('Error in PembelianController@show: ' . $e->getMessage());
            return redirect()->route('pembelian.index')->with('error', 'Pembelian tidak ditemukan.');
        }
    }

    /**
     * Get pembelian statistics for dashboard
     */
    public function getStats()
    {
        try {
            $today = now()->startOfDay();
            $thisMonth = now()->startOfMonth();
            $thisYear = now()->startOfYear();

            $stats = [
                'today_purchases' => Pembelian::whereDate('tanggal', $today)->sum('total_harga'),
                'this_month_purchases' => Pembelian::whereDate('tanggal', '>=', $thisMonth)->sum('total_harga'),
                'this_year_purchases' => Pembelian::whereDate('tanggal', '>=', $thisYear)->sum('total_harga'),
                'total_transactions' => Pembelian::count(),
                'today_transactions' => Pembelian::whereDate('tanggal', $today)->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Error in PembelianController@getStats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get statistics'], 500);
        }
    }
}
