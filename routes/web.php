<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\SukuCadangController;
use App\Http\Controllers\TransaksiMasukController;
use App\Http\Controllers\TransaksiKeluarController;
use App\Http\Controllers\NotifikasiRopController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ─── Redirect root ke halaman role (atau login jika belum auth) ─────────────
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->users_role;
        switch ($role) {
            case 'spv':
                return redirect()->route('dashboard');
            case 'staf_inventory':
                return redirect()->route('suku-cadang.index');
            case 'admin_gudang':
                return redirect()->route('transaksi-masuk.index');
        }
    }
    return redirect()->route('login');
});

// ─── Auth Routes (Guest Only) ──────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ─── Logout ────────────────────────────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Protected Routes (Auth Only) ─────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // ─── SPV Only ──────────────────────────────────────────────────────────
    Route::middleware('role:spv')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::get('/laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    });

    // ─── Staf Inventory Only ────────────────────────────────────────────────
    Route::middleware('role:staf_inventory')->group(function () {
        Route::resource('supplier', SupplierController::class);
        Route::resource('kendaraan', KendaraanController::class);
        Route::resource('suku-cadang', SukuCadangController::class);
        Route::get('/notifikasi-rop', [NotifikasiRopController::class, 'index'])->name('notifikasi-rop.index');
        Route::post('/notifikasi-rop/{id}/resolve', [NotifikasiRopController::class, 'resolve'])->name('notifikasi-rop.resolve');
    });

    // ─── Admin Gudang Only ──────────────────────────────────────────────────
    Route::middleware('role:admin_gudang')->group(function () {
        Route::resource('transaksi-masuk', TransaksiMasukController::class);
        Route::resource('transaksi-keluar', TransaksiKeluarController::class);
    });

    // ─── All Authenticated Roles ─────────────────────────────────────────────
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

