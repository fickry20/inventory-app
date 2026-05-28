<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        // Jika sudah login, arahkan ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses login.
     */

    public function login(Request $request)
    {
        $request->validate([
            'users_email'         => ['required', 'email'],
            'users_password_hash' => ['required', 'string', 'min:6'],
        ], [
            'users_email.required'         => 'Email wajib diisi.',
            'users_email.email'            => 'Format email tidak valid.',
            'users_password_hash.required' => 'Password wajib diisi.',
            'users_password_hash.min'      => 'Password minimal 6 karakter.',
        ]);

        $user = User::where('users_email', $request->users_email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('users_email'))
                ->with('error', 'Email atau password salah.');
        }

        if (!Hash::check($request->users_password_hash, $user->users_password_hash)) {
            return back()
                ->withInput($request->only('users_email'))
                ->with('error', 'Email atau password salah.');
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang, ' . $user->users_username . '!');
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
