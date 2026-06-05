<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $kendaraans = Kendaraan::query()
            ->when($search, function ($query, $search) {
                $query->where('kendaraan_plat', 'like', "%{$search}%")
                    ->orWhere('kendaraan_jenis', 'like', "%{$search}%")
                    ->orWhere('kendaraan_nama_driver', 'like', "%{$search}%");
            })
            ->latest('kendaraan_created_at')
            ->paginate(10)
            ->withQueryString();

        return view('kendaraan.index', compact('kendaraans', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kendaraan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kendaraan_plat'        => 'required|string|max:20|unique:kendaraan,kendaraan_plat',
            'kendaraan_jenis'       => 'required|in:box,engkel',
            'kendaraan_nama_driver' => 'required|string|max:150',
        ], [
            'kendaraan_plat.required'        => 'Plat kendaraan wajib diisi.',
            'kendaraan_plat.max'             => 'Plat kendaraan maksimal 20 karakter.',
            'kendaraan_plat.unique'          => 'Plat kendaraan sudah terdaftar.',
            'kendaraan_jenis.required'       => 'Jenis kendaraan wajib dipilih.',
            'kendaraan_jenis.in'             => 'Jenis kendaraan harus box atau engkel.',
            'kendaraan_nama_driver.required' => 'Nama driver wajib diisi.',
            'kendaraan_nama_driver.max'      => 'Nama driver maksimal 150 karakter.',
        ]);

        $kendaraan = Kendaraan::create($validated);

        \App\Helpers\ActivityLogger::log('CREATE_KENDARAAN', $kendaraan, 'Menambahkan kendaraan baru: ' . $kendaraan->kendaraan_plat . ' (Driver: ' . $kendaraan->kendaraan_nama_driver . ')');

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        return view('kendaraan.edit', compact('kendaraan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $validated = $request->validate([
            'kendaraan_plat' => [
                'required',
                'string',
                'max:20',
                Rule::unique('kendaraan', 'kendaraan_plat')->ignore($kendaraan->kendaraan_id, 'kendaraan_id'),
            ],
            'kendaraan_jenis'       => 'required|in:box,engkel',
            'kendaraan_nama_driver' => 'required|string|max:150',
        ], [
            'kendaraan_plat.required'        => 'Plat kendaraan wajib diisi.',
            'kendaraan_plat.max'             => 'Plat kendaraan maksimal 20 karakter.',
            'kendaraan_plat.unique'          => 'Plat kendaraan sudah terdaftar.',
            'kendaraan_jenis.required'       => 'Jenis kendaraan wajib dipilih.',
            'kendaraan_jenis.in'             => 'Jenis kendaraan harus box atau engkel.',
            'kendaraan_nama_driver.required' => 'Nama driver wajib diisi.',
            'kendaraan_nama_driver.max'      => 'Nama driver maksimal 150 karakter.',
        ]);

        $kendaraan->update($validated);

        \App\Helpers\ActivityLogger::log('UPDATE_KENDARAAN', $kendaraan, 'Mengubah data kendaraan: ' . $kendaraan->kendaraan_plat);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        \App\Helpers\ActivityLogger::log('DELETE_KENDARAAN', $kendaraan, 'Menghapus kendaraan: ' . $kendaraan->kendaraan_plat);

        $kendaraan->delete();

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil dihapus.');
    }
}
