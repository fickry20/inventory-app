<?php

namespace Tests\Feature\BlackBox;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

/**
 * BLACK BOX TEST — Autentikasi
 *
 * Menguji fitur login dan logout berdasarkan input/output yang terlihat
 * tanpa melihat detail implementasi internal.
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ─── Helper: Buat user untuk testing ────────────────────────────────────
    private function createUser(string $role = 'spv'): User
    {
        return User::create([
            'users_nik'           => '1234567890' . rand(10, 99),
            'users_username'      => $role . '_test_' . rand(100, 999),
            'users_email'         => $role . rand(100, 999) . '@test.com',
            'users_password_hash' => 'password123',
            'users_jabatan'       => 'Tester',
            'users_nomor_telepon' => '08123456789',
            'users_role'          => $role,
        ]);
    }

    // =========================================================================
    // TC-AUTH-01: Halaman login dapat diakses
    // =========================================================================
    public function test_halaman_login_dapat_diakses_oleh_tamu(): void
    {
        // INPUT  : GET /login (tanpa login)
        // EKSPEK : HTTP 200, konten form login tampil
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('login', false); // ada elemen login di halaman
    }

    // =========================================================================
    // TC-AUTH-02: Login dengan kredensial valid — SPV
    // =========================================================================
    public function test_login_berhasil_dengan_kredensial_valid_spv(): void
    {
        $user = $this->createUser('spv');

        // INPUT  : POST /login dengan email & password benar
        // EKSPEK : Redirect ke /dashboard
        $response = $this->post('/login', [
            'users_email'         => $user->users_email,
            'users_password_hash' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    // =========================================================================
    // TC-AUTH-03: Login berhasil — Staf Inventory
    // =========================================================================
    public function test_login_berhasil_dengan_kredensial_valid_staf_inventory(): void
    {
        $user = $this->createUser('staf_inventory');

        // INPUT  : POST /login dengan email & password benar
        // EKSPEK : Redirect ke /suku-cadang
        $response = $this->post('/login', [
            'users_email'         => $user->users_email,
            'users_password_hash' => 'password123',
        ]);

        $response->assertRedirect('/suku-cadang');
        $this->assertAuthenticatedAs($user);
    }

    // =========================================================================
    // TC-AUTH-04: Login berhasil — Admin Gudang
    // =========================================================================
    public function test_login_berhasil_dengan_kredensial_valid_admin_gudang(): void
    {
        $user = $this->createUser('admin_gudang');

        // INPUT  : POST /login dengan email & password benar
        // EKSPEK : Redirect ke /transaksi-masuk
        $response = $this->post('/login', [
            'users_email'         => $user->users_email,
            'users_password_hash' => 'password123',
        ]);

        $response->assertRedirect('/transaksi-masuk');
        $this->assertAuthenticatedAs($user);
    }

    // =========================================================================
    // TC-AUTH-05: Login gagal — password salah
    // =========================================================================
    public function test_login_gagal_jika_password_salah(): void
    {
        $user = $this->createUser('spv');

        // Mengunjungi halaman login agar rute referer 'back()' terisi
        $this->get('/login');

        // INPUT  : POST /login dengan password yang SALAH
        // EKSPEK : Kembali ke /login (bukan redirect ke dashboard), user tidak terautentikasi
        $response = $this->post('/login', [
            'users_email'         => $user->users_email,
            'users_password_hash' => 'password_salah',
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    // =========================================================================
    // TC-AUTH-06: Login gagal — email tidak terdaftar
    // =========================================================================
    public function test_login_gagal_jika_email_tidak_ada(): void
    {
        $this->get('/login');

        // INPUT  : POST /login dengan email yang tidak ada
        // EKSPEK : Redirect kembali dengan error, tidak terautentikasi
        $response = $this->post('/login', [
            'users_email'         => 'tidak_ada@test.com',
            'users_password_hash' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    // =========================================================================
    // TC-AUTH-07: Login gagal — field kosong
    // =========================================================================
    public function test_login_gagal_jika_field_kosong(): void
    {
        $this->get('/login');

        // INPUT  : POST /login tanpa email dan password
        // EKSPEK : Ada error validasi, tidak terautentikasi
        $response = $this->post('/login', [
            'users_email'         => '',
            'users_password_hash' => '',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    // =========================================================================
    // TC-AUTH-08: Halaman login tidak bisa diakses setelah login
    // =========================================================================
    public function test_user_yang_sudah_login_diarahkan_dari_halaman_login(): void
    {
        $user = $this->createUser('spv');

        // INPUT  : GET /login saat sudah terautentikasi
        // EKSPEK : Diredirect (bukan tampil form login lagi)
        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect();
    }

    // =========================================================================
    // TC-AUTH-09: Logout berhasil
    // =========================================================================
    public function test_logout_berhasil_dan_sesi_dihapus(): void
    {
        $user = $this->createUser('spv');

        // INPUT  : POST /logout saat sudah terautentikasi
        // EKSPEK : Diredirect ke /login, user tidak terautentikasi lagi
        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    // =========================================================================
    // TC-AUTH-10: Halaman protected tidak bisa diakses tanpa login
    // =========================================================================
    public function test_halaman_dashboard_tidak_bisa_diakses_tanpa_login(): void
    {
        // INPUT  : GET /dashboard tanpa autentikasi
        // EKSPEK : Redirect ke /login
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}
