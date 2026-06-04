<?php

namespace App\Http\Controllers;

use App\Models\PerusahaanTujuan;
use Illuminate\Http\Request;

class PerusahaanTujuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $perusahaans = PerusahaanTujuan::query()
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('kontak', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('perusahaan_tujuan.index', compact('perusahaans', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('perusahaan_tujuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:150',
            'kontak' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
        ], [
            'nama.required' => 'Nama perusahaan wajib diisi.',
            'nama.max'      => 'Nama perusahaan maksimal 150 karakter.',
            'kontak.max'    => 'Kontak maksimal 100 karakter.',
        ]);

        PerusahaanTujuan::create($validated);

        return redirect()->route('perusahaan-tujuan.index')
            ->with('success', 'Data perusahaan tujuan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $perusahaan = PerusahaanTujuan::findOrFail($id);
        return view('perusahaan_tujuan.edit', compact('perusahaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $perusahaan = PerusahaanTujuan::findOrFail($id);

        $validated = $request->validate([
            'nama'   => 'required|string|max:150',
            'kontak' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
        ], [
            'nama.required' => 'Nama perusahaan wajib diisi.',
            'nama.max'      => 'Nama perusahaan maksimal 150 karakter.',
            'kontak.max'    => 'Kontak maksimal 100 karakter.',
        ]);

        $perusahaan->update($validated);

        return redirect()->route('perusahaan-tujuan.index')
            ->with('success', 'Data perusahaan tujuan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $perusahaan = PerusahaanTujuan::findOrFail($id);

        // Check if there are outgoing transactions using this company
        if ($perusahaan->transaksiKeluar()->exists()) {
            return back()->with('error', 'Perusahaan tujuan tidak dapat dihapus karena telah digunakan dalam transaksi keluar.');
        }

        $perusahaan->delete();

        return redirect()->route('perusahaan-tujuan.index')
            ->with('success', 'Data perusahaan tujuan berhasil dihapus.');
    }
}
