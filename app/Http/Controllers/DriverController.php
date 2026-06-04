<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    /**
     * Store a newly created driver in storage.
     */
    public function store(Request $request, $supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);

        $validated = $request->validate([
            'nama_driver'    => 'required|string|max:150',
            'plat_kendaraan' => 'required|string|max:20',
            'no_surat_jalan' => 'required|string|max:100',
            'foto_sj'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ], [
            'nama_driver.required'    => 'Nama driver wajib diisi.',
            'nama_driver.max'         => 'Nama driver maksimal 150 karakter.',
            'plat_kendaraan.required' => 'Plat kendaraan wajib diisi.',
            'plat_kendaraan.max'      => 'Plat kendaraan maksimal 20 karakter.',
            'no_surat_jalan.required' => 'Nomor surat jalan wajib diisi.',
            'no_surat_jalan.max'      => 'Nomor surat jalan maksimal 100 karakter.',
            'foto_sj.image'           => 'Foto Surat Jalan harus berupa berkas gambar.',
            'foto_sj.mimes'           => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'foto_sj.max'             => 'Ukuran gambar maksimal 4 MB.',
        ]);

        if ($request->hasFile('foto_sj')) {
            $path = $request->file('foto_sj')->store('sj_photos', 'public');
            $validated['foto_sj'] = $path;
        }

        $validated['supplier_id'] = $supplier->supplier_id;

        Driver::create($validated);

        return redirect()->route('supplier.edit', $supplier->supplier_id)
            ->with('success', 'Driver berhasil ditambahkan.');
    }

    /**
     * Update the specified driver in storage.
     */
    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $validated = $request->validate([
            'nama_driver'    => 'required|string|max:150',
            'plat_kendaraan' => 'required|string|max:20',
            'no_surat_jalan' => 'required|string|max:100',
            'foto_sj'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ], [
            'nama_driver.required'    => 'Nama driver wajib diisi.',
            'nama_driver.max'         => 'Nama driver maksimal 150 karakter.',
            'plat_kendaraan.required' => 'Plat kendaraan wajib diisi.',
            'plat_kendaraan.max'      => 'Plat kendaraan maksimal 20 karakter.',
            'no_surat_jalan.required' => 'Nomor surat jalan wajib diisi.',
            'no_surat_jalan.max'      => 'Nomor surat jalan maksimal 100 karakter.',
            'foto_sj.image'           => 'Foto Surat Jalan harus berupa berkas gambar.',
            'foto_sj.mimes'           => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'foto_sj.max'             => 'Ukuran gambar maksimal 4 MB.',
        ]);

        if ($request->hasFile('foto_sj')) {
            // Delete old photo if exists
            if ($driver->foto_sj) {
                Storage::disk('public')->delete($driver->foto_sj);
            }
            $path = $request->file('foto_sj')->store('sj_photos', 'public');
            $validated['foto_sj'] = $path;
        }

        $driver->update($validated);

        return redirect()->route('supplier.edit', $driver->supplier_id)
            ->with('success', 'Data driver berhasil diperbarui.');
    }

    /**
     * Remove the specified driver from storage.
     */
    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $supplierId = $driver->supplier_id;

        // Delete photo if exists
        if ($driver->foto_sj) {
            Storage::disk('public')->delete($driver->foto_sj);
        }

        $driver->delete();

        return redirect()->route('supplier.edit', $supplierId)
            ->with('success', 'Driver berhasil dihapus.');
    }

    /**
     * API to fetch drivers for a supplier (used in Barang Masuk dropdown).
     */
    public function getDriversBySupplier($supplierId)
    {
        $drivers = Driver::where('supplier_id', $supplierId)->get();
        return response()->json($drivers);
    }
}
