<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $suppliers = Supplier::query()
            ->when($search, function ($query, $search) {
                $query->where('supplier_nama', 'like', "%{$search}%")
                    ->orWhere('supplier_plat_kendaraan', 'like', "%{$search}%")
                    ->orWhere('supplier_nama_driver', 'like', "%{$search}%");
            })
            ->latest('supplier_created_at')
            ->paginate(10)
            ->withQueryString();

        return view('supplier.index', compact('suppliers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_nama'           => 'required|string|max:150',
            'supplier_kontak'         => 'required|string|max:100',
            'supplier_alamat'         => 'required|string',
        ], [
            'supplier_nama.required'           => 'Nama supplier wajib diisi.',
            'supplier_nama.max'                => 'Nama supplier maksimal 150 karakter.',
            'supplier_kontak.required'         => 'Kontak supplier wajib diisi.',
            'supplier_kontak.max'              => 'Kontak supplier maksimal 100 karakter.',
            'supplier_alamat.required'         => 'Alamat supplier wajib diisi.',
        ]);

        $supplier = Supplier::create($validated);

        \App\Helpers\ActivityLogger::log('CREATE_SUPPLIER', $supplier, 'Menambahkan supplier baru: ' . $supplier->supplier_nama);

        return redirect()->route('supplier.index')
            ->with('success', 'Data supplier berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $supplier = Supplier::with('drivers')->findOrFail($id);
        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'supplier_nama'           => 'required|string|max:150',
            'supplier_kontak'         => 'required|string|max:100',
            'supplier_alamat'         => 'required|string',
        ], [
            'supplier_nama.required'           => 'Nama supplier wajib diisi.',
            'supplier_nama.max'                => 'Nama supplier maksimal 150 karakter.',
            'supplier_kontak.required'         => 'Kontak supplier wajib diisi.',
            'supplier_kontak.max'              => 'Kontak supplier maksimal 100 karakter.',
            'supplier_alamat.required'         => 'Alamat supplier wajib diisi.',
        ]);

        $supplier->update($validated);

        \App\Helpers\ActivityLogger::log('UPDATE_SUPPLIER', $supplier, 'Mengubah data supplier: ' . $supplier->supplier_nama);

        return redirect()->route('supplier.index')
            ->with('success', 'Data supplier berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        \App\Helpers\ActivityLogger::log('DELETE_SUPPLIER', $supplier, 'Menghapus supplier: ' . $supplier->supplier_nama);

        $supplier->delete();

        return redirect()->route('supplier.index')
            ->with('success', 'Data supplier berhasil dihapus.');
    }
}
