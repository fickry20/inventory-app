<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the user profile form.
     */
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

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
            'users_nomor_telepon' => 'required|string|max:20',
            'password'            => 'nullable|string|min:6|confirmed',
        ], [
            'users_nik.required'           => 'NIK wajib diisi.',
            'users_nik.max'                => 'NIK maksimal 20 karakter.',
            'users_nik.unique'             => 'NIK sudah terdaftar pada akun lain.',
            'users_username.required'      => 'Username wajib diisi.',
            'users_username.max'           => 'Username maksimal 100 karakter.',
            'users_username.unique'        => 'Username sudah terdaftar pada akun lain.',
            'users_email.required'         => 'Email wajib diisi.',
            'users_email.email'            => 'Format email tidak valid.',
            'users_email.max'              => 'Email maksimal 150 karakter.',
            'users_email.unique'           => 'Email sudah terdaftar pada akun lain.',
            'users_nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'users_nomor_telepon.max'      => 'Nomor telepon maksimal 20 karakter.',
            'password.min'                 => 'Password minimal 6 karakter.',
            'password.confirmed'           => 'Konfirmasi password tidak cocok.',
        ]);

        $updateData = [
            'users_nik'           => $validated['users_nik'],
            'users_username'      => $validated['users_username'],
            'users_email'         => $validated['users_email'],
            'users_nomor_telepon' => $validated['users_nomor_telepon'],
        ];

        // Jika password diisi, hash password baru
        if (!empty($validated['password'])) {
            $updateData['users_password_hash'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('profile')
            ->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
