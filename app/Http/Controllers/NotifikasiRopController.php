<?php

namespace App\Http\Controllers;

use App\Models\NotifikasiRop;
use Illuminate\Http\Request;

class NotifikasiRopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = NotifikasiRop::with('sukuCadang')
            ->orderBy('rop_sudah_ditangani', 'asc')
            ->latest('rop_created_at')
            ->paginate(15);

        return view('notifikasi_rop.index', compact('notifications'));
    }

    /**
     * Mark the notification as resolved.
     */
    public function resolve($id)
    {
        $notification = NotifikasiRop::findOrFail($id);
        $notification->update([
            'rop_sudah_ditangani' => true,
        ]);

        $notification->load('sukuCadang');
        \App\Helpers\ActivityLogger::log('RESOLVE_ROP', $notification, 'Menyelesaikan peringatan ROP untuk suku cadang: ' . ($notification->sukuCadang->suku_cadang_nama ?? 'Dihapus'));

        return redirect()->route('notifikasi-rop.index')
            ->with('success', 'Notifikasi ROP berhasil ditangani (ditandai selesai).');
    }
}
