<?php

namespace App\Http\Controllers;

use App\Models\SukuCadang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SukuCadangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $sukuCadangs = SukuCadang::with('supplier')
            ->when($search, function ($query, $search) {
                $query->where('suku_cadang_kode', 'like', "%{$search}%")
                    ->orWhere('suku_cadang_nama', 'like', "%{$search}%")
                    ->orWhere('suku_cadang_kategori', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('supplier_nama', 'like', "%{$search}%");
                    });
            })
            ->latest('suku_cadang_created_at')
            ->paginate(10)
            ->withQueryString();

        return view('suku_cadang.index', compact('sukuCadangs', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('suku_cadang.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'suku_cadang_supplier_id'   => 'required|exists:supplier,supplier_id',
            'suku_cadang_kode'          => 'required|string|max:50|unique:suku_cadang,suku_cadang_kode',
            'suku_cadang_nama'          => 'required|string|max:150',
            'suku_cadang_kategori'      => 'required|string|max:100',
            'suku_cadang_satuan'        => 'required|string|max:20',
            'suku_cadang_stok_total'    => 'required|integer|min:0',
            'suku_cadang_reorder_point' => 'required|integer|min:0',
            'suku_cadang_stok_minimum'  => 'required|integer|min:0',
        ], [
            'suku_cadang_supplier_id.required'   => 'Supplier wajib dipilih.',
            'suku_cadang_supplier_id.exists'     => 'Supplier tidak valid.',
            'suku_cadang_kode.required'          => 'Kode suku cadang wajib diisi.',
            'suku_cadang_kode.max'               => 'Kode suku cadang maksimal 50 karakter.',
            'suku_cadang_kode.unique'            => 'Kode suku cadang sudah terdaftar.',
            'suku_cadang_nama.required'          => 'Nama suku cadang wajib diisi.',
            'suku_cadang_nama.max'               => 'Nama suku cadang maksimal 150 karakter.',
            'suku_cadang_kategori.required'      => 'Kategori wajib diisi.',
            'suku_cadang_kategori.max'          => 'Kategori maksimal 100 karakter.',
            'suku_cadang_satuan.required'        => 'Satuan wajib diisi.',
            'suku_cadang_satuan.max'            => 'Satuan maksimal 20 karakter.',
            'suku_cadang_stok_total.required'    => 'Stok awal wajib diisi.',
            'suku_cadang_stok_total.integer'     => 'Stok awal harus berupa angka.',
            'suku_cadang_stok_total.min'         => 'Stok awal tidak boleh kurang dari 0.',
            'suku_cadang_reorder_point.required' => 'Reorder point wajib diisi.',
            'suku_cadang_reorder_point.integer'  => 'Reorder point harus berupa angka.',
            'suku_cadang_reorder_point.min'      => 'Reorder point tidak boleh kurang dari 0.',
            'suku_cadang_stok_minimum.required'  => 'Stok minimum wajib diisi.',
            'suku_cadang_stok_minimum.integer'   => 'Stok minimum harus berupa angka.',
            'suku_cadang_stok_minimum.min'       => 'Stok minimum tidak boleh kurang dari 0.',
        ]);

        $sukuCadang = SukuCadang::create($validated);

        \App\Helpers\ActivityLogger::log('CREATE_SUKU_CADANG', $sukuCadang, 'Menambahkan suku cadang baru: ' . $sukuCadang->suku_cadang_nama . ' (' . $sukuCadang->suku_cadang_kode . ')');

        return redirect()->route('suku-cadang.index')
            ->with('success', 'Data suku cadang berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $sukuCadang = SukuCadang::findOrFail($id);
        $suppliers = Supplier::all();
        return view('suku_cadang.edit', compact('sukuCadang', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sukuCadang = SukuCadang::findOrFail($id);

        $validated = $request->validate([
            'suku_cadang_supplier_id'   => 'required|exists:supplier,supplier_id',
            'suku_cadang_kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('suku_cadang', 'suku_cadang_kode')->ignore($sukuCadang->suku_cadang_id, 'suku_cadang_id'),
            ],
            'suku_cadang_nama'          => 'required|string|max:150',
            'suku_cadang_kategori'      => 'required|string|max:100',
            'suku_cadang_satuan'        => 'required|string|max:20',
            'suku_cadang_stok_total'    => 'required|integer|min:0',
            'suku_cadang_reorder_point' => 'required|integer|min:0',
            'suku_cadang_stok_minimum'  => 'required|integer|min:0',
        ], [
            'suku_cadang_supplier_id.required'   => 'Supplier wajib dipilih.',
            'suku_cadang_supplier_id.exists'     => 'Supplier tidak valid.',
            'suku_cadang_kode.required'          => 'Kode suku cadang wajib diisi.',
            'suku_cadang_kode.max'               => 'Kode suku cadang maksimal 50 karakter.',
            'suku_cadang_kode.unique'            => 'Kode suku cadang sudah terdaftar.',
            'suku_cadang_nama.required'          => 'Nama suku cadang wajib diisi.',
            'suku_cadang_nama.max'               => 'Nama suku cadang maksimal 150 karakter.',
            'suku_cadang_kategori.required'      => 'Kategori wajib diisi.',
            'suku_cadang_kategori.max'          => 'Kategori maksimal 100 karakter.',
            'suku_cadang_satuan.required'        => 'Satuan wajib diisi.',
            'suku_cadang_satuan.max'            => 'Satuan maksimal 20 karakter.',
            'suku_cadang_stok_total.required'    => 'Stok total wajib diisi.',
            'suku_cadang_stok_total.integer'     => 'Stok total harus berupa angka.',
            'suku_cadang_stok_total.min'         => 'Stok total tidak boleh kurang dari 0.',
            'suku_cadang_reorder_point.required' => 'Reorder point wajib diisi.',
            'suku_cadang_reorder_point.integer'  => 'Reorder point harus berupa angka.',
            'suku_cadang_reorder_point.min'      => 'Reorder point tidak boleh kurang dari 0.',
            'suku_cadang_stok_minimum.required'  => 'Stok minimum wajib diisi.',
            'suku_cadang_stok_minimum.integer'   => 'Stok minimum harus berupa angka.',
            'suku_cadang_stok_minimum.min'       => 'Stok minimum tidak boleh kurang dari 0.',
        ]);

        $sukuCadang->update($validated);

        \App\Helpers\ActivityLogger::log('UPDATE_SUKU_CADANG', $sukuCadang, 'Mengubah data suku cadang: ' . $sukuCadang->suku_cadang_nama . ' (' . $sukuCadang->suku_cadang_kode . ')');

        return redirect()->route('suku-cadang.index')
            ->with('success', 'Data suku cadang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sukuCadang = SukuCadang::findOrFail($id);

        \App\Helpers\ActivityLogger::log('DELETE_SUKU_CADANG', $sukuCadang, 'Menghapus suku cadang: ' . $sukuCadang->suku_cadang_nama . ' (' . $sukuCadang->suku_cadang_kode . ')');

        $sukuCadang->delete();

        return redirect()->route('suku-cadang.index')
            ->with('success', 'Data suku cadang berhasil dihapus.');
    }
}
