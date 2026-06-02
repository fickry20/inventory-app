<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMasuk;
use App\Models\BatchMasuk;
use App\Models\SukuCadang;
use App\Models\Supplier;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $transaksiMasuks = TransaksiMasuk::with(['sukuCadang', 'supplier', 'user', 'kendaraan'])
            ->when($search, function ($query, $search) {
                $query->where('transaksi_masuk_no_dokumen', 'like', "%{$search}%")
                    ->orWhere('transaksi_masuk_no_surat_jalan', 'like', "%{$search}%")
                    ->orWhereHas('sukuCadang', function ($q) use ($search) {
                        $q->where('suku_cadang_nama', 'like', "%{$search}%")
                            ->orWhere('suku_cadang_kode', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('supplier_nama', 'like', "%{$search}%");
                    });
            })
            ->latest('transaksi_masuk_created_at')
            ->paginate(10)
            ->withQueryString();

        return view('transaksi_masuk.index', compact('transaksiMasuks', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sukuCadangs = SukuCadang::all();
        $suppliers = Supplier::all();
        $kendaraans = Kendaraan::all();

        return view('transaksi_masuk.create', compact('sukuCadangs', 'suppliers', 'kendaraans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaksi_masuk_suku_cadang_id' => 'required|exists:suku_cadang,suku_cadang_id',
            'transaksi_masuk_supplier_id'    => 'required|exists:supplier,supplier_id',
            'transaksi_masuk_kendaraan_id'   => 'nullable|exists:kendaraan,kendaraan_id',
            'transaksi_masuk_no_dokumen'     => 'required|string|max:100',
            'transaksi_masuk_no_surat_jalan' => 'required|string|max:100',
            'transaksi_masuk_jumlah'         => 'required|integer|min:1',
            'transaksi_masuk_keterangan'     => 'nullable|string',
        ], [
            'transaksi_masuk_suku_cadang_id.required' => 'Suku cadang wajib dipilih.',
            'transaksi_masuk_suku_cadang_id.exists'   => 'Suku cadang tidak valid.',
            'transaksi_masuk_supplier_id.required'    => 'Supplier wajib dipilih.',
            'transaksi_masuk_supplier_id.exists'      => 'Supplier tidak valid.',
            'transaksi_masuk_kendaraan_id.exists'      => 'Kendaraan tidak valid.',
            'transaksi_masuk_no_dokumen.required'     => 'Nomor dokumen wajib diisi.',
            'transaksi_masuk_no_dokumen.max'          => 'Nomor dokumen maksimal 100 karakter.',
            'transaksi_masuk_no_surat_jalan.required' => 'Nomor surat jalan wajib diisi.',
            'transaksi_masuk_no_surat_jalan.max'      => 'Nomor surat jalan maksimal 100 karakter.',
            'transaksi_masuk_jumlah.required'         => 'Kuantitas barang masuk wajib diisi.',
            'transaksi_masuk_jumlah.integer'          => 'Kuantitas harus berupa angka.',
            'transaksi_masuk_jumlah.min'              => 'Kuantitas barang masuk minimal 1.',
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Simpan Transaksi Masuk
            $transaksi = TransaksiMasuk::create([
                'transaksi_masuk_suku_cadang_id' => $validated['transaksi_masuk_suku_cadang_id'],
                'transaksi_masuk_supplier_id'    => $validated['transaksi_masuk_supplier_id'],
                'transaksi_masuk_users_id'       => auth()->id(),
                'transaksi_masuk_kendaraan_id'   => $validated['transaksi_masuk_kendaraan_id'] ?? null,
                'transaksi_masuk_no_dokumen'     => $validated['transaksi_masuk_no_dokumen'],
                'transaksi_masuk_no_surat_jalan' => $validated['transaksi_masuk_no_surat_jalan'],
                'transaksi_masuk_jumlah'         => $validated['transaksi_masuk_jumlah'],
                'transaksi_masuk_keterangan'     => $validated['transaksi_masuk_keterangan'] ?? null,
            ]);

            // 2. Simpan Batch Masuk untuk FIFO
            BatchMasuk::create([
                'suku_cadang_id'  => $validated['transaksi_masuk_suku_cadang_id'],
                'transaksi_masuk' => $transaksi->transaksi_masuk_id,
                'jumlah_awal'     => $validated['transaksi_masuk_jumlah'],
                'jumlah_sisa'     => $validated['transaksi_masuk_jumlah'],
                'tanggal_masuk'   => Carbon::now(),
                'is_habis'        => false,
            ]);

            // 3. Tambahkan stok total di Suku Cadang
            $sukuCadang = SukuCadang::findOrFail($validated['transaksi_masuk_suku_cadang_id']);
            $sukuCadang->increment('suku_cadang_stok_total', $validated['transaksi_masuk_jumlah']);
        });

        return redirect()->route('transaksi-masuk.index')
            ->with('success', 'Transaksi masuk berhasil dicatat dan batch FIFO diaktifkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $transaksiMasuk = TransaksiMasuk::with('batchMasuk')->findOrFail($id);

        // Periksa apakah batch dari transaksi masuk ini sudah terpakai
        $hasBeenUsed = false;
        foreach ($transaksiMasuk->batchMasuk as $batch) {
            if ($batch->jumlah_sisa < $batch->jumlah_awal) {
                $hasBeenUsed = true;
                break;
            }
        }

        $sukuCadangs = SukuCadang::all();
        $suppliers = Supplier::all();
        $kendaraans = Kendaraan::all();

        return view('transaksi_masuk.edit', compact('transaksiMasuk', 'hasBeenUsed', 'sukuCadangs', 'suppliers', 'kendaraans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $transaksiMasuk = TransaksiMasuk::with('batchMasuk')->findOrFail($id);

        // Periksa apakah batch dari transaksi masuk ini sudah terpakai
        $hasBeenUsed = false;
        foreach ($transaksiMasuk->batchMasuk as $batch) {
            if ($batch->jumlah_sisa < $batch->jumlah_awal) {
                $hasBeenUsed = true;
                break;
            }
        }

        $rules = [
            'transaksi_masuk_supplier_id'    => 'required|exists:supplier,supplier_id',
            'transaksi_masuk_kendaraan_id'   => 'nullable|exists:kendaraan,kendaraan_id',
            'transaksi_masuk_no_dokumen'     => 'required|string|max:100',
            'transaksi_masuk_no_surat_jalan' => 'required|string|max:100',
            'transaksi_masuk_keterangan'     => 'nullable|string',
        ];

        // Jika belum terpakai, izinkan mengedit Suku Cadang dan Jumlah
        if (!$hasBeenUsed) {
            $rules['transaksi_masuk_suku_cadang_id'] = 'required|exists:suku_cadang,suku_cadang_id';
            $rules['transaksi_masuk_jumlah']         = 'required|integer|min:1';
        }

        $validated = $request->validate($rules, [
            'transaksi_masuk_supplier_id.required'    => 'Supplier wajib dipilih.',
            'transaksi_masuk_supplier_id.exists'      => 'Supplier tidak valid.',
            'transaksi_masuk_kendaraan_id.exists'      => 'Kendaraan tidak valid.',
            'transaksi_masuk_no_dokumen.required'     => 'Nomor dokumen wajib diisi.',
            'transaksi_masuk_no_dokumen.max'          => 'Nomor dokumen maksimal 100 karakter.',
            'transaksi_masuk_no_surat_jalan.required' => 'Nomor surat jalan wajib diisi.',
            'transaksi_masuk_no_surat_jalan.max'      => 'Nomor surat jalan maksimal 100 karakter.',
            'transaksi_masuk_suku_cadang_id.required' => 'Suku cadang wajib dipilih.',
            'transaksi_masuk_suku_cadang_id.exists'   => 'Suku cadang tidak valid.',
            'transaksi_masuk_jumlah.required'         => 'Kuantitas barang masuk wajib diisi.',
            'transaksi_masuk_jumlah.integer'          => 'Kuantitas harus berupa angka.',
            'transaksi_masuk_jumlah.min'              => 'Kuantitas barang masuk minimal 1.',
        ]);

        DB::transaction(function () use ($transaksiMasuk, $validated, $hasBeenUsed) {
            if (!$hasBeenUsed) {
                $oldSukuCadangId = $transaksiMasuk->transaksi_masuk_suku_cadang_id;
                $newSukuCadangId = $validated['transaksi_masuk_suku_cadang_id'];
                $oldJumlah = $transaksiMasuk->transaksi_masuk_jumlah;
                $newJumlah = $validated['transaksi_masuk_jumlah'];

                // Jika suku cadang berubah
                if ($oldSukuCadangId != $newSukuCadangId) {
                    // Kurangi stok suku cadang lama
                    $oldSuku = SukuCadang::findOrFail($oldSukuCadangId);
                    $oldSuku->decrement('suku_cadang_stok_total', $oldJumlah);

                    // Tambahkan stok suku cadang baru
                    $newSuku = SukuCadang::findOrFail($newSukuCadangId);
                    $newSuku->increment('suku_cadang_stok_total', $newJumlah);
                } else {
                    // Jika suku cadang sama tapi kuantitas berubah
                    $diff = $newJumlah - $oldJumlah;
                    $suku = SukuCadang::findOrFail($oldSukuCadangId);
                    if ($diff > 0) {
                        $suku->increment('suku_cadang_stok_total', $diff);
                    } elseif ($diff < 0) {
                        $suku->decrement('suku_cadang_stok_total', abs($diff));
                    }
                }

                // Update Batch Masuk terkait
                foreach ($transaksiMasuk->batchMasuk as $batch) {
                    $batch->update([
                        'suku_cadang_id' => $newSukuCadangId,
                        'jumlah_awal'    => $newJumlah,
                        'jumlah_sisa'    => $newJumlah,
                    ]);
                }

                // Update data transaksi masuk (termasuk kuantitas dan suku cadang)
                $transaksiMasuk->update([
                    'transaksi_masuk_suku_cadang_id' => $newSukuCadangId,
                    'transaksi_masuk_jumlah'         => $newJumlah,
                ]);
            }

            // Update data transaksi masuk (metadata)
            $transaksiMasuk->update([
                'transaksi_masuk_supplier_id'    => $validated['transaksi_masuk_supplier_id'],
                'transaksi_masuk_kendaraan_id'   => $validated['transaksi_masuk_kendaraan_id'] ?? null,
                'transaksi_masuk_no_dokumen'     => $validated['transaksi_masuk_no_dokumen'],
                'transaksi_masuk_no_surat_jalan' => $validated['transaksi_masuk_no_surat_jalan'],
                'transaksi_masuk_keterangan'     => $validated['transaksi_masuk_keterangan'] ?? null,
            ]);
        });

        return redirect()->route('transaksi-masuk.index')
            ->with('success', 'Transaksi masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaksiMasuk = TransaksiMasuk::with('batchMasuk')->findOrFail($id);

        // Periksa apakah batch dari transaksi masuk ini sudah terpakai
        $hasBeenUsed = false;
        foreach ($transaksiMasuk->batchMasuk as $batch) {
            if ($batch->jumlah_sisa < $batch->jumlah_awal) {
                $hasBeenUsed = true;
                break;
            }
        }

        if ($hasBeenUsed) {
            return back()->with('error', 'Transaksi masuk tidak dapat dihapus karena stok dari batch ini sudah digunakan oleh transaksi keluar.');
        }

        DB::transaction(function () use ($transaksiMasuk) {
            foreach ($transaksiMasuk->batchMasuk as $batch) {
                // Kurangi stok suku cadang
                $sukuCadang = SukuCadang::findOrFail($batch->suku_cadang_id);
                $sukuCadang->decrement('suku_cadang_stok_total', $batch->jumlah_awal);

                // Hapus Batch Masuk secara permanen / soft delete sesuai model
                $batch->delete();
            }

            // Soft delete transaksi masuk
            $transaksiMasuk->delete();
        });

        return redirect()->route('transaksi-masuk.index')
            ->with('success', 'Transaksi masuk berhasil dihapus.');
    }
}
