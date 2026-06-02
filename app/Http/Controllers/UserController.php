<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('users_nik', 'like', "%{$search}%")
                    ->orWhere('users_username', 'like', "%{$search}%")
                    ->orWhere('users_email', 'like', "%{$search}%")
                    ->orWhere('users_jabatan', 'like', "%{$search}%");
            })
            ->latest('users_created_at')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'users_nik'           => 'required|string|max:20|unique:users,users_nik',
            'users_username'      => 'required|string|max:100|unique:users,users_username',
            'users_email'         => 'required|email|max:150|unique:users,users_email',
            'password'            => 'required|string|min:6|confirmed',
            'users_jabatan'       => 'required|string|max:100',
            'users_nomor_telepon' => 'required|string|max:20',
            'users_role'          => 'required|in:admin_gudang,staf_inventory,spv',
        ], [
            'users_nik.required'           => 'NIK wajib diisi.',
            'users_nik.max'                => 'NIK maksimal 20 karakter.',
            'users_nik.unique'             => 'NIK sudah terdaftar.',
            'users_username.required'      => 'Username wajib diisi.',
            'users_username.max'           => 'Username maksimal 100 karakter.',
            'users_username.unique'        => 'Username sudah terdaftar.',
            'users_email.required'         => 'Email wajib diisi.',
            'users_email.email'            => 'Format email tidak valid.',
            'users_email.max'              => 'Email maksimal 150 karakter.',
            'users_email.unique'           => 'Email sudah terdaftar.',
            'password.required'            => 'Password wajib diisi.',
            'password.min'                 => 'Password minimal 6 karakter.',
            'password.confirmed'           => 'Konfirmasi password tidak cocok.',
            'users_jabatan.required'       => 'Jabatan wajib diisi.',
            'users_jabatan.max'            => 'Jabatan maksimal 100 karakter.',
            'users_nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'users_nomor_telepon.max'      => 'Nomor telepon maksimal 20 karakter.',
            'users_role.required'          => 'Role user wajib dipilih.',
            'users_role.in'                => 'Role user tidak valid.',
        ]);

        User::create([
            'users_nik'           => $validated['users_nik'],
            'users_username'      => $validated['users_username'],
            'users_email'         => $validated['users_email'],
            'users_password_hash' => Hash::make($validated['password']),
            'users_jabatan'       => $validated['users_jabatan'],
            'users_nomor_telepon' => $validated['users_nomor_telepon'],
            'users_role'          => $validated['users_role'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Akun user berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'users_nik' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'users_nik')->ignore($user->users_id, 'users_id'),
            ],
            'users_username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'users_username')->ignore($user->users_id, 'users_id'),
            ],
            'users_email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'users_email')->ignore($user->users_id, 'users_id'),
            ],
            'password'            => 'nullable|string|min:6|confirmed',
            'users_jabatan'       => 'required|string|max:100',
            'users_nomor_telepon' => 'required|string|max:20',
            'users_role'          => 'required|in:admin_gudang,staf_inventory,spv',
        ], [
            'users_nik.required'           => 'NIK wajib diisi.',
            'users_nik.max'                => 'NIK maksimal 20 karakter.',
            'users_nik.unique'             => 'NIK sudah terdaftar.',
            'users_username.required'      => 'Username wajib diisi.',
            'users_username.max'           => 'Username maksimal 100 karakter.',
            'users_username.unique'        => 'Username sudah terdaftar.',
            'users_email.required'         => 'Email wajib diisi.',
            'users_email.email'            => 'Format email tidak valid.',
            'users_email.max'              => 'Email maksimal 150 karakter.',
            'users_email.unique'           => 'Email sudah terdaftar.',
            'password.min'                 => 'Password minimal 6 karakter.',
            'password.confirmed'           => 'Konfirmasi password tidak cocok.',
            'users_jabatan.required'       => 'Jabatan wajib diisi.',
            'users_jabatan.max'            => 'Jabatan maksimal 100 karakter.',
            'users_nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'users_nomor_telepon.max'      => 'Nomor telepon maksimal 20 karakter.',
            'users_role.required'          => 'Role user wajib dipilih.',
            'users_role.in'                => 'Role user tidak valid.',
        ]);

        $updateData = [
            'users_nik'           => $validated['users_nik'],
            'users_username'      => $validated['users_username'],
            'users_email'         => $validated['users_email'],
            'users_jabatan'       => $validated['users_jabatan'],
            'users_nomor_telepon' => $validated['users_nomor_telepon'],
            'users_role'          => $validated['users_role'],
        ];

        // Jika password diisi, enkripsi dan simpan password baru
        if (!empty($validated['password'])) {
            $updateData['users_password_hash'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Keamanan: Cegah menghapus diri sendiri
        if ($user->users_id == auth()->id()) {
            return back()->with('error', 'Keamanan Sistem: Anda tidak diizinkan menghapus akun Anda sendiri yang sedang aktif.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Akun user berhasil dihapus.');
    }
}
