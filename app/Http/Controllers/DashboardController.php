<?php

namespace App\Http\Controllers;

use App\Models\SukuCadang;
use App\Models\Supplier;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use App\Models\NotifikasiRop;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dynamic dashboard.
     */
    public function index()
    {
        // 1. Data Ringkasan Card
        $totalSukuCadang = SukuCadang::count();
        $totalSupplier = Supplier::count();
        $totalTransaksiMasuk = TransaksiMasuk::count();
        $activeRopCount = NotifikasiRop::where('rop_sudah_ditangani', false)->count();

        // 2. Data Pie Chart (Sebaran Kategori)
        $categories = SukuCadang::select('suku_cadang_kategori', DB::raw('count(*) as total'))
            ->groupBy('suku_cadang_kategori')
            ->orderBy('total', 'desc')
            ->take(5) // Batasi 5 kategori teratas
            ->get();

        $pieLabels = $categories->pluck('suku_cadang_kategori')->toArray();
        $pieData = $categories->pluck('total')->toArray();

        // 3. Data Area Chart (Tren Distribusi Stok Masuk vs Keluar 6 Bulan Terakhir)
        $monthlyMasuk = TransaksiMasuk::select(
                DB::raw("DATE_FORMAT(transaksi_masuk_created_at, '%Y-%m') as month"), 
                DB::raw("SUM(transaksi_masuk_jumlah) as total")
            )
            ->where('transaksi_masuk_created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $monthlyKeluar = TransaksiKeluar::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), 
                DB::raw("SUM(jumlah_terpenuhi) as total")
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $chartLabels = [];
        $chartMasukData = [];
        $chartKeluarData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->translatedFormat('M Y');

            $chartLabels[] = $monthLabel;
            $chartMasukData[] = (int) ($monthlyMasuk[$monthKey] ?? 0);
            $chartKeluarData[] = (int) ($monthlyKeluar[$monthKey] ?? 0);
        }

        // 4. Log Riwayat Terbaru (Masing-masing 5 Transaksi Terakhir)
        $recentMasuk = TransaksiMasuk::with(['sukuCadang', 'supplier'])
            ->latest('transaksi_masuk_created_at')
            ->take(5)
            ->get();

        $recentKeluar = TransaksiKeluar::with(['sukuCadang', 'kendaraan'])
            ->latest('created_at')
            ->take(5)
            ->get();

        // 5. Data Bar Chart (Top 5 Suku Cadang Paling Banyak Keluar)
        $topSukuCadang = TransaksiKeluar::select('suku_cadang_id', DB::raw('SUM(jumlah_terpenuhi) as total'))
            ->groupBy('suku_cadang_id')
            ->orderBy('total', 'desc')
            ->with('sukuCadang')
            ->take(5)
            ->get();

        $topLabels = [];
        $topData = [];
        foreach ($topSukuCadang as $item) {
            $topLabels[] = $item->sukuCadang->suku_cadang_nama ?? 'Dihapus';
            $topData[] = (int) $item->total;
        }

        // 6. Daftar Suku Cadang yang Kurang dari ROP (Batas Aman)
        $ropSukuCadangs = SukuCadang::with('supplier')
            ->whereColumn('suku_cadang_stok_total', '<=', 'suku_cadang_reorder_point')
            ->orderBy('suku_cadang_stok_total', 'asc')
            ->get();

        return view('dashboard', compact(
            'totalSukuCadang',
            'totalSupplier',
            'totalTransaksiMasuk',
            'activeRopCount',
            'pieLabels',
            'pieData',
            'chartLabels',
            'chartMasukData',
            'chartKeluarData',
            'recentMasuk',
            'recentKeluar',
            'topLabels',
            'topData',
            'ropSukuCadangs'
        ));
    }
}
