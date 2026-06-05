<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKeluar;
use App\Models\DetailKeluarFifo;
use App\Models\BatchMasuk;
use App\Models\SukuCadang;
use App\Models\Kendaraan;
use App\Models\NotifikasiRop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiKeluarController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin_gudang')->except(['index', 'show', 'cetakSj']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $transaksiKeluars = TransaksiKeluar::with(['sukuCadang', 'user', 'kendaraan', 'perusahaanTujuan'])
            ->when($search, function ($query, $search) {
                $query->where('no_surat_jalan', 'like', "%{$search}%")
                    ->orWhereHas('sukuCadang', function ($q) use ($search) {
                        $q->where('suku_cadang_nama', 'like', "%{$search}%")
                            ->orWhere('suku_cadang_kode', 'like', "%{$search}%");
                    })
                    ->orWhereHas('perusahaanTujuan', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('users_username', 'like', "%{$search}%");
                    });
            })
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('transaksi_keluar.index', compact('transaksiKeluars', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sukuCadangs = SukuCadang::all();
        $kendaraans = Kendaraan::all();
        $perusahaans = \App\Models\PerusahaanTujuan::all();

        return view('transaksi_keluar.create', compact('sukuCadangs', 'kendaraans', 'perusahaans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'suku_cadang_id' => 'required|exists:suku_cadang,suku_cadang_id',
            'kendaraan_id'   => 'required|exists:kendaraan,kendaraan_id',
            'no_surat_jalan' => 'required|string|max:100',
            'tujuan_pt_id'   => 'required|exists:perusahaan_tujuan,id',
            'jumlah_diminta' => 'required|integer|min:1',
            'keterangan'     => 'nullable|string',
        ], [
            'suku_cadang_id.required' => 'Suku cadang wajib dipilih.',
            'suku_cadang_id.exists'   => 'Suku cadang tidak valid.',
            'kendaraan_id.required'   => 'Kendaraan pengirim wajib dipilih.',
            'kendaraan_id.exists'      => 'Kendaraan tidak valid.',
            'no_surat_jalan.required' => 'Nomor surat jalan wajib diisi.',
            'no_surat_jalan.max'      => 'Nomor surat jalan maksimal 100 karakter.',
            'tujuan_pt_id.required'   => 'Perusahaan tujuan wajib dipilih.',
            'tujuan_pt_id.exists'     => 'Perusahaan tujuan tidak valid.',
            'jumlah_diminta.required' => 'Jumlah barang keluar wajib diisi.',
            'jumlah_diminta.integer'  => 'Jumlah barang keluar harus berupa angka.',
            'jumlah_diminta.min'      => 'Jumlah barang keluar minimal 1.',
        ]);

        $sukuCadang = SukuCadang::findOrFail($request->suku_cadang_id);
        if ($sukuCadang->suku_cadang_stok_total < $request->jumlah_diminta) {
            return back()->withErrors(['jumlah_diminta' => 'Stok tidak cukup! (Stok saat ini: ' . $sukuCadang->suku_cadang_stok_total . ')'])->withInput();
        }

        $transaksi = DB::transaction(function () use ($validated) {
            $sukuCadangId = $validated['suku_cadang_id'];
            $requestedQty = $validated['jumlah_diminta'];

            // 1. Buat records Transaksi Keluar terlebih dahulu
            $transaksi = TransaksiKeluar::create([
                'suku_cadang_id'   => $sukuCadangId,
                'users'            => auth()->id(),
                'kendaraan_id'     => $validated['kendaraan_id'],
                'no_surat_jalan'   => $validated['no_surat_jalan'],
                'tujuan_pt_id'     => $validated['tujuan_pt_id'],
                'jumlah_diminta'   => $requestedQty,
                'jumlah_terpenuhi' => 0, // diupdate nanti
                'status'           => 'ditolak', // diupdate nanti
                'keterangan'       => $validated['keterangan'] ?? null,
            ]);

            // 2. Ambil batch masuk aktif tertua (FIFO)
            $batches = BatchMasuk::where('suku_cadang_id', $sukuCadangId)
                ->where('is_habis', false)
                ->orderBy('tanggal_masuk', 'asc')
                ->get();

            $fulfilledQty = 0;

            foreach ($batches as $batch) {
                $remainingToFulfill = $requestedQty - $fulfilledQty;
                if ($remainingToFulfill <= 0) {
                    break;
                }

                $canTake = min($batch->jumlah_sisa, $remainingToFulfill);

                // Update batch
                $batch->jumlah_sisa -= $canTake;
                if ($batch->jumlah_sisa <= 0) {
                    $batch->is_habis = true;
                }
                $batch->save();

                // Buat detail FIFO
                DetailKeluarFifo::create([
                    'transaksi_keluar_id' => $transaksi->transaksi_keluar_id,
                    'batch_masuk_id'      => $batch->batch_masuk_id,
                    'fifo_jumlah_diambil' => $canTake,
                ]);

                $fulfilledQty += $canTake;
            }

            // 3. Update status transaksi
            $status = 'ditolak';
            if ($fulfilledQty == $requestedQty) {
                $status = 'terpenuhi';
            } elseif ($fulfilledQty > 0) {
                $status = 'sebagian';
            }

            $transaksi->update([
                'jumlah_terpenuhi' => $fulfilledQty,
                'status'           => $status,
            ]);

            // 4. Kurangi stok total di Suku Cadang
            if ($fulfilledQty > 0) {
                $sukuCadang = SukuCadang::findOrFail($sukuCadangId);
                $sukuCadang->decrement('suku_cadang_stok_total', $fulfilledQty);

                // 5. Cek Reorder Point (ROP) untuk memicu notifikasi
                $newStock = $sukuCadang->suku_cadang_stok_total;
                if ($newStock <= $sukuCadang->suku_cadang_reorder_point) {
                    $alreadyNotified = NotifikasiRop::where('suku_cadang_id', $sukuCadangId)
                        ->where('rop_sudah_ditangani', false)
                        ->exists();

                    if (!$alreadyNotified) {
                        NotifikasiRop::create([
                            'suku_cadang_id'      => $sukuCadangId,
                            'rop_stok_saat_notif' => $newStock,
                            'rop_rop_saat_notif'  => $sukuCadang->suku_cadang_reorder_point,
                            'rop_sudah_ditangani' => false,
                        ]);
                    }
                }
            }

            return $transaksi;
        });

        $transaksi->load('sukuCadang');
        \App\Helpers\ActivityLogger::log('CREATE_TRANSAKSI_KELUAR', $transaksi, 'Mencatat barang keluar: ' . $transaksi->jumlah_terpenuhi . ' pcs suku cadang ' . ($transaksi->sukuCadang->suku_cadang_nama ?? 'Dihapus') . ' dengan nomor surat jalan ' . $transaksi->no_surat_jalan);

        return redirect()->route('transaksi-keluar.index')
            ->with('success', 'Transaksi keluar berhasil diproses dengan metode pemotongan FIFO.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $transaksiKeluar = TransaksiKeluar::findOrFail($id);
        $sukuCadangs = SukuCadang::all();
        $kendaraans = Kendaraan::all();
        $perusahaans = \App\Models\PerusahaanTujuan::all();

        return view('transaksi_keluar.edit', compact('transaksiKeluar', 'sukuCadangs', 'kendaraans', 'perusahaans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $transaksiKeluar = TransaksiKeluar::with('detailKeluarFifo')->findOrFail($id);

        $validated = $request->validate([
            'suku_cadang_id' => 'required|exists:suku_cadang,suku_cadang_id',
            'kendaraan_id'   => 'required|exists:kendaraan,kendaraan_id',
            'no_surat_jalan' => 'required|string|max:100',
            'tujuan_pt_id'   => 'required|exists:perusahaan_tujuan,id',
            'jumlah_diminta' => 'required|integer|min:1',
            'keterangan'     => 'nullable|string',
        ], [
            'suku_cadang_id.required' => 'Suku cadang wajib dipilih.',
            'suku_cadang_id.exists'   => 'Suku cadang tidak valid.',
            'kendaraan_id.required'   => 'Kendaraan pengirim wajib dipilih.',
            'kendaraan_id.exists'      => 'Kendaraan tidak valid.',
            'no_surat_jalan.required' => 'Nomor surat jalan wajib diisi.',
            'no_surat_jalan.max'      => 'Nomor surat jalan maksimal 100 karakter.',
            'tujuan_pt_id.required'   => 'Perusahaan tujuan wajib dipilih.',
            'tujuan_pt_id.exists'     => 'Perusahaan tujuan tidak valid.',
            'jumlah_diminta.required' => 'Jumlah barang keluar wajib diisi.',
            'jumlah_diminta.integer'  => 'Jumlah barang keluar harus berupa angka.',
            'jumlah_diminta.min'      => 'Jumlah barang keluar minimal 1.',
        ]);

        $sukuCadang = SukuCadang::findOrFail($request->suku_cadang_id);
        $tempAvailableStock = $sukuCadang->suku_cadang_stok_total;
        if ($request->suku_cadang_id == $transaksiKeluar->suku_cadang_id) {
            $tempAvailableStock += $transaksiKeluar->jumlah_terpenuhi;
        }
        if ($tempAvailableStock < $request->jumlah_diminta) {
            return back()->withErrors(['jumlah_diminta' => 'Stok tidak cukup! (Stok tersedia: ' . $tempAvailableStock . ')'])->withInput();
        }

        DB::transaction(function () use ($transaksiKeluar, $validated) {
            // --- ROLLBACK FIFO YANG LAMA ---
            // Kembalikan sisa stok ke batch masing-masing
            foreach ($transaksiKeluar->detailKeluarFifo as $detail) {
                if ($detail->batchMasuk) {
                    $batch = $detail->batchMasuk;
                    $batch->jumlah_sisa += $detail->fifo_jumlah_diambil;
                    $batch->is_habis = false;
                    $batch->save();
                }
                // Hapus detail keluar secara paksa
                $detail->forceDelete();
            }

            // Kembalikan stok total Suku Cadang lama
            if ($transaksiKeluar->jumlah_terpenuhi > 0) {
                $oldSuku = SukuCadang::findOrFail($transaksiKeluar->suku_cadang_id);
                $oldSuku->increment('suku_cadang_stok_total', $transaksiKeluar->jumlah_terpenuhi);
            }

            // --- REAPPLY FIFO DENGAN DATA BARU ---
            $newSukuCadangId = $validated['suku_cadang_id'];
            $newRequestedQty = $validated['jumlah_diminta'];

            $batches = BatchMasuk::where('suku_cadang_id', $newSukuCadangId)
                ->where('is_habis', false)
                ->orderBy('tanggal_masuk', 'asc')
                ->get();

            $fulfilledQty = 0;

            foreach ($batches as $batch) {
                $remainingToFulfill = $newRequestedQty - $fulfilledQty;
                if ($remainingToFulfill <= 0) {
                    break;
                }

                $canTake = min($batch->jumlah_sisa, $remainingToFulfill);

                // Update batch
                $batch->jumlah_sisa -= $canTake;
                if ($batch->jumlah_sisa <= 0) {
                    $batch->is_habis = true;
                }
                $batch->save();

                // Buat detail FIFO
                DetailKeluarFifo::create([
                    'transaksi_keluar_id' => $transaksiKeluar->transaksi_keluar_id,
                    'batch_masuk_id'      => $batch->batch_masuk_id,
                    'fifo_jumlah_diambil' => $canTake,
                ]);

                $fulfilledQty += $canTake;
            }

            $status = 'ditolak';
            if ($fulfilledQty == $newRequestedQty) {
                $status = 'terpenuhi';
            } elseif ($fulfilledQty > 0) {
                $status = 'sebagian';
            }

            // Update Suku Cadang baru
            if ($fulfilledQty > 0) {
                $newSuku = SukuCadang::findOrFail($newSukuCadangId);
                $newSuku->decrement('suku_cadang_stok_total', $fulfilledQty);

                // Cek ROP baru
                $newStock = $newSuku->suku_cadang_stok_total;
                if ($newStock <= $newSuku->suku_cadang_reorder_point) {
                    $alreadyNotified = NotifikasiRop::where('suku_cadang_id', $newSukuCadangId)
                        ->where('rop_sudah_ditangani', false)
                        ->exists();

                    if (!$alreadyNotified) {
                        NotifikasiRop::create([
                            'suku_cadang_id'      => $newSukuCadangId,
                            'rop_stok_saat_notif' => $newStock,
                            'rop_rop_saat_notif'  => $newSuku->suku_cadang_reorder_point,
                            'rop_sudah_ditangani' => false,
                        ]);
                    }
                }
            }

            // Update transaksi keluar
            $transaksiKeluar->update([
                'suku_cadang_id'   => $newSukuCadangId,
                'kendaraan_id'     => $validated['kendaraan_id'],
                'no_surat_jalan'   => $validated['no_surat_jalan'],
                'tujuan_pt_id'     => $validated['tujuan_pt_id'],
                'jumlah_diminta'   => $newRequestedQty,
                'jumlah_terpenuhi' => $fulfilledQty,
                'status'           => $status,
                'keterangan'       => $validated['keterangan'] ?? null,
            ]);
        });

        \App\Helpers\ActivityLogger::log('UPDATE_TRANSAKSI_KELUAR', $transaksiKeluar, 'Mengubah data transaksi keluar dengan nomor surat jalan ' . $transaksiKeluar->no_surat_jalan);

        return redirect()->route('transaksi-keluar.index')
            ->with('success', 'Transaksi keluar berhasil diperbarui dan alokasi FIFO disesuaikan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaksiKeluar = TransaksiKeluar::with('detailKeluarFifo')->findOrFail($id);

        DB::transaction(function () use ($transaksiKeluar) {
            // --- ROLLBACK FIFO ---
            foreach ($transaksiKeluar->detailKeluarFifo as $detail) {
                if ($detail->batchMasuk) {
                    $batch = $detail->batchMasuk;
                    $batch->jumlah_sisa += $detail->fifo_jumlah_diambil;
                    $batch->is_habis = false;
                    $batch->save();
                }
                $detail->forceDelete();
            }

            // Kembalikan stok suku cadang
            if ($transaksiKeluar->jumlah_terpenuhi > 0) {
                $sukuCadang = SukuCadang::findOrFail($transaksiKeluar->suku_cadang_id);
                $sukuCadang->increment('suku_cadang_stok_total', $transaksiKeluar->jumlah_terpenuhi);
            }

            // Soft delete transaksi
            $transaksiKeluar->delete();
        });

        \App\Helpers\ActivityLogger::log('DELETE_TRANSAKSI_KELUAR', $transaksiKeluar, 'Menghapus transaksi keluar dengan nomor surat jalan ' . $transaksiKeluar->no_surat_jalan);

        return redirect()->route('transaksi-keluar.index')
            ->with('success', 'Transaksi keluar berhasil dihapus dan alokasi stok dikembalikan.');
    }

    /**
     * Cetak Surat Jalan (Delivery Order) untuk Transaksi Keluar.
     */
    public function cetakSj($id)
    {
        $transaksi = TransaksiKeluar::with(['sukuCadang', 'user', 'kendaraan', 'perusahaanTujuan'])->findOrFail($id);

        \App\Helpers\ActivityLogger::log('PRINT_SURAT_JALAN', $transaksi, 'Mencetak surat jalan untuk transaksi keluar dengan nomor surat jalan ' . $transaksi->no_surat_jalan);

        return view('transaksi_keluar.cetak_sj', compact('transaksi'));
    }
}
