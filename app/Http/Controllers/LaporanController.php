<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan persediaan untuk Supervisor.
     */
    public function index(Request $request)
    {
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');
        $type = $request->input('type', 'semua');

        // Default: bulan ini
        $startDate = $startDateInput ? Carbon::parse($startDateInput)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
        $endDate = $endDateInput ? Carbon::parse($endDateInput)->endOfDay() : Carbon::now()->endOfDay();

        $transaksiMasuk = collect();
        $transaksiKeluar = collect();

        if ($type === 'semua' || $type === 'masuk') {
            $transaksiMasuk = TransaksiMasuk::with(['sukuCadang', 'supplier', 'user', 'kendaraan'])
                ->whereBetween('transaksi_masuk_created_at', [$startDate, $endDate])
                ->orderBy('transaksi_masuk_created_at', 'desc')
                ->get();
        }

        if ($type === 'semua' || $type === 'keluar') {
            $transaksiKeluar = TransaksiKeluar::with(['sukuCadang', 'user', 'kendaraan', 'perusahaanTujuan'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('laporan.index', [
            'transaksiMasuk' => $transaksiMasuk,
            'transaksiKeluar' => $transaksiKeluar,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'type' => $type,
        ]);
    }
}
