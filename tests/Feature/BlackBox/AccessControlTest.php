<?php

namespace Tests\Feature\BlackBox;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * BLACK BOX TEST — Kontrol Akses (Access Control)
 *
 * Memastikan setiap role HANYA dapat mengakses halaman yang diizinkan.
 * Akses ke halaman role lain harus ditolak (403 Forbidden atau redirect).
 */
class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    private User $spv;
    private User $staf;
    private User $adminGudang;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spv = User::create([
            'users_nik'           => '1111111111111111',
            'users_username'      => 'spv_akses_test',
            'users_email'         => 'spv.akses@test.com',
            'users_password_hash' => 'password123',
            'users_jabatan'       => 'Supervisor',
            'users_nomor_telepon' => '08100000001',
            'users_role'          => 'spv',
        ]);

        $this->staf = User::create([
            'users_nik'           => '2222222222222222',
            'users_username'      => 'staf_akses_test',
            'users_email'         => 'staf.akses@test.com',
            'users_password_hash' => 'password123',
            'users_jabatan'       => 'Staf',
            'users_nomor_telepon' => '08100000002',
            'users_role'          => 'staf_inventory',
        ]);

        $this->adminGudang = User::create([
            'users_nik'           => '3333333333333333',
            'users_username'      => 'admin_akses_test',
            'users_email'         => 'admin.akses@test.com',
            'users_password_hash' => 'password123',
            'users_jabatan'       => 'Admin',
            'users_nomor_telepon' => '08100000003',
            'users_role'          => 'admin_gudang',
        ]);
    }

    // =========================================================================
    // STAF INVENTORY — tidak boleh akses halaman SPV
    // =========================================================================

    /** TC-ACL-01: Staf inventory tidak bisa akses dashboard (khusus SPV) */
    public function test_staf_tidak_dapat_akses_dashboard(): void
    {
        // INPUT  : GET /dashboard sebagai staf_inventory
        // EKSPEK : Ditolak (403 atau redirect)
        $response = $this->actingAs($this->staf)->get('/dashboard');

        $this->assertNotEquals(200, $response->status(),
            'Staf inventory seharusnya tidak dapat akses dashboard SPV.');
    }

    /** TC-ACL-02: Staf inventory tidak bisa akses manajemen user */
    public function test_staf_tidak_dapat_akses_manajemen_user(): void
    {
        // INPUT  : GET /users sebagai staf_inventory
        // EKSPEK : Ditolak (403 atau redirect)
        $response = $this->actingAs($this->staf)->get('/users');

        $this->assertNotEquals(200, $response->status(),
            'Staf inventory seharusnya tidak dapat akses manajemen user.');
    }

    /** TC-ACL-03: Staf inventory tidak bisa akses laporan */
    public function test_staf_tidak_dapat_akses_laporan(): void
    {
        // INPUT  : GET /laporan sebagai staf_inventory
        // EKSPEK : Ditolak (403 atau redirect)
        $response = $this->actingAs($this->staf)->get('/laporan');

        $this->assertNotEquals(200, $response->status());
    }

    // =========================================================================
    // ADMIN GUDANG — tidak boleh akses halaman SPV / Staf Inventory
    // =========================================================================

    /** TC-ACL-04: Admin gudang tidak bisa akses dashboard (khusus SPV) */
    public function test_admin_gudang_tidak_dapat_akses_dashboard(): void
    {
        // INPUT  : GET /dashboard sebagai admin_gudang
        // EKSPEK : Ditolak (403 atau redirect)
        $response = $this->actingAs($this->adminGudang)->get('/dashboard');

        $this->assertNotEquals(200, $response->status(),
            'Admin gudang seharusnya tidak dapat akses dashboard SPV.');
    }

    /** TC-ACL-05: Admin gudang tidak bisa akses manajemen user */
    public function test_admin_gudang_tidak_dapat_akses_manajemen_user(): void
    {
        // INPUT  : GET /users sebagai admin_gudang
        // EKSPEK : Ditolak (403 atau redirect)
        $response = $this->actingAs($this->adminGudang)->get('/users');

        $this->assertNotEquals(200, $response->status());
    }

    /** TC-ACL-06: Admin gudang tidak bisa akses data master suku cadang */
    public function test_admin_gudang_tidak_dapat_akses_suku_cadang(): void
    {
        // INPUT  : GET /suku-cadang sebagai admin_gudang
        // EKSPEK : Ditolak (403 atau redirect)
        $response = $this->actingAs($this->adminGudang)->get('/suku-cadang');

        $this->assertNotEquals(200, $response->status());
    }

    /** TC-ACL-07: Admin gudang tidak bisa akses supplier */
    public function test_admin_gudang_tidak_dapat_akses_supplier(): void
    {
        // INPUT  : GET /supplier sebagai admin_gudang
        // EKSPEK : Ditolak (403 atau redirect)
        $response = $this->actingAs($this->adminGudang)->get('/supplier');

        $this->assertNotEquals(200, $response->status());
    }

    // =========================================================================
    // SPV — tidak boleh CREATE/EDIT/DELETE transaksi (khusus admin_gudang)
    // =========================================================================

    /** TC-ACL-08: SPV tidak bisa akses form catat barang masuk */
    public function test_spv_tidak_dapat_akses_form_create_transaksi_masuk(): void
    {
        // INPUT  : GET /transaksi-masuk/create sebagai SPV
        // EKSPEK : Ditolak (403 atau redirect)
        $response = $this->actingAs($this->spv)->get('/transaksi-masuk/create');

        $this->assertNotEquals(200, $response->status(),
            'SPV seharusnya tidak dapat membuat transaksi masuk (hanya admin gudang).');
    }

    /** TC-ACL-09: SPV tidak bisa mengakses form create transaksi keluar */
    public function test_spv_tidak_dapat_akses_form_create_transaksi_keluar(): void
    {
        // INPUT  : GET /transaksi-keluar/create sebagai SPV
        // EKSPEK : Ditolak (403 atau redirect)
        $response = $this->actingAs($this->spv)->get('/transaksi-keluar/create');

        $this->assertNotEquals(200, $response->status());
    }

    // =========================================================================
    // TAMU (GUEST) — tidak boleh akses halaman mana pun
    // =========================================================================

    /** TC-ACL-10: Tamu tidak bisa akses /dashboard */
    public function test_tamu_tidak_dapat_akses_dashboard(): void
    {
        // INPUT  : GET /dashboard tanpa autentikasi
        // EKSPEK : Redirect ke /login
        $this->get('/dashboard')->assertRedirect('/login');
    }

    /** TC-ACL-11: Tamu tidak bisa akses /supplier */
    public function test_tamu_tidak_dapat_akses_supplier(): void
    {
        // INPUT  : GET /supplier tanpa autentikasi
        // EKSPEK : Redirect ke /login
        $this->get('/supplier')->assertRedirect('/login');
    }

    /** TC-ACL-12: Tamu tidak bisa akses /transaksi-masuk */
    public function test_tamu_tidak_dapat_akses_transaksi_masuk(): void
    {
        // INPUT  : GET /transaksi-masuk tanpa autentikasi
        // EKSPEK : Redirect ke /login
        $this->get('/transaksi-masuk')->assertRedirect('/login');
    }

    /** TC-ACL-13: Tamu tidak bisa akses /profile */
    public function test_tamu_tidak_dapat_akses_profile(): void
    {
        // INPUT  : GET /profile tanpa autentikasi
        // EKSPEK : Redirect ke /login
        $this->get('/profile')->assertRedirect('/login');
    }

    // =========================================================================
    // AKSES YANG DIIZINKAN SEMUA ROLE
    // =========================================================================

    /** TC-ACL-14: Semua role dapat akses halaman profil */
    public function test_semua_role_dapat_akses_profil(): void
    {
        // INPUT  : GET /profile sebagai masing-masing role
        // EKSPEK : HTTP 200 untuk semua role
        foreach ([$this->spv, $this->staf, $this->adminGudang] as $user) {
            $this->actingAs($user)
                ->get('/profile')
                ->assertStatus(200);
        }
    }

    /** TC-ACL-15: Semua role dapat akses activity log */
    public function test_semua_role_dapat_akses_activity_log(): void
    {
        // INPUT  : GET /activity-log sebagai masing-masing role
        // EKSPEK : HTTP 200 untuk semua role
        foreach ([$this->spv, $this->staf, $this->adminGudang] as $user) {
            $this->actingAs($user)
                ->get('/activity-log')
                ->assertStatus(200);
        }
    }
}
