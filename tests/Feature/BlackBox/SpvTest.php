<?php

namespace Tests\Feature\BlackBox;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * BLACK BOX TEST — Fitur Role SPV (Supervisor)
 *
 * Menguji semua halaman dan aksi yang dapat dilakukan oleh role SPV.
 * Pengujian dilakukan dari perspektif pengguna (input/output), tanpa
 * melihat implementasi internal.
 */
class SpvTest extends TestCase
{
    use RefreshDatabase;

    private User $spv;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spv = User::create([
            'users_nik'           => '3201010101010003',
            'users_username'      => 'spv_test',
            'users_email'         => 'spv@test.com',
            'users_password_hash' => 'password123',
            'users_jabatan'       => 'Supervisor',
            'users_nomor_telepon' => '081234567892',
            'users_role'          => 'spv',
        ]);
    }

    // =========================================================================
    // DASHBOARD
    // =========================================================================

    /** TC-SPV-01: Dashboard dapat diakses oleh SPV */
    public function test_dashboard_dapat_diakses_oleh_spv(): void
    {
        // INPUT  : GET /dashboard sebagai SPV
        // EKSPEK : HTTP 200
        $this->actingAs($this->spv)
            ->get('/dashboard')
            ->assertStatus(200);
    }

    /** TC-SPV-02: Dashboard menampilkan konten yang benar */
    public function test_dashboard_menampilkan_elemen_utama(): void
    {
        // INPUT  : GET /dashboard sebagai SPV
        // EKSPEK : Halaman mengandung kata 'Dashboard' atau statistik
        $this->actingAs($this->spv)
            ->get('/dashboard')
            ->assertStatus(200)
            ->assertSee('Dashboard');
    }

    // =========================================================================
    // MANAJEMEN USER
    // =========================================================================

    /** TC-SPV-03: Halaman daftar user dapat diakses */
    public function test_halaman_daftar_user_dapat_diakses(): void
    {
        // INPUT  : GET /users
        // EKSPEK : HTTP 200
        $this->actingAs($this->spv)
            ->get('/users')
            ->assertStatus(200);
    }

    /** TC-SPV-04: Halaman form tambah user dapat diakses */
    public function test_halaman_form_tambah_user_dapat_diakses(): void
    {
        // INPUT  : GET /users/create
        // EKSPEK : HTTP 200, ada form
        $this->actingAs($this->spv)
            ->get('/users/create')
            ->assertStatus(200);
    }

    /** TC-SPV-05: SPV dapat membuat user baru dengan data valid */
    public function test_spv_dapat_membuat_user_baru(): void
    {
        // INPUT  : POST /users dengan data lengkap dan valid
        // EKSPEK : Redirect ke /users, data tersimpan di DB
        $response = $this->actingAs($this->spv)->post('/users', [
            'users_nik'            => '9999999999999999',
            'users_username'       => 'user_baru_test',
            'users_email'          => 'userbaru@test.com',
            'password'             => 'password123',
            'password_confirmation'=> 'password123',
            'users_jabatan'        => 'Tester',
            'users_nomor_telepon'  => '08111222333',
            'users_role'           => 'admin_gudang',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['users_username' => 'user_baru_test']);
    }

    /** TC-SPV-06: Gagal buat user jika NIK duplikat */
    public function test_gagal_buat_user_jika_nik_duplikat(): void
    {
        // INPUT  : POST /users dengan NIK yang sudah ada
        // EKSPEK : Kembali ke form dengan error validasi
        $response = $this->actingAs($this->spv)->post('/users', [
            'users_nik'            => '3201010101010003', // NIK spv yang sudah ada
            'users_username'       => 'user_duplikat',
            'users_email'          => 'duplikat@test.com',
            'password'             => 'password123',
            'password_confirmation'=> 'password123',
            'users_jabatan'        => 'Tester',
            'users_nomor_telepon'  => '08111222333',
            'users_role'           => 'admin_gudang',
        ]);

        $response->assertSessionHasErrors(['users_nik']);
    }

    /** TC-SPV-07: Gagal buat user jika password confirmation tidak cocok */
    public function test_gagal_buat_user_jika_password_tidak_cocok(): void
    {
        // INPUT  : POST /users dengan password_confirmation berbeda
        // EKSPEK : Error validasi pada field password
        $response = $this->actingAs($this->spv)->post('/users', [
            'users_nik'            => '8888888888888888',
            'users_username'       => 'user_pwd_test',
            'users_email'          => 'pwdtest@test.com',
            'password'             => 'password123',
            'password_confirmation'=> 'berbeda_salah',
            'users_jabatan'        => 'Tester',
            'users_nomor_telepon'  => '08111222333',
            'users_role'           => 'admin_gudang',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** TC-SPV-08: Halaman edit user dapat diakses */
    public function test_halaman_edit_user_dapat_diakses(): void
    {
        // INPUT  : GET /users/{id}/edit
        // EKSPEK : HTTP 200
        $this->actingAs($this->spv)
            ->get("/users/{$this->spv->users_id}/edit")
            ->assertStatus(200);
    }

    /** TC-SPV-09: SPV dapat mengupdate data user */
    public function test_spv_dapat_mengupdate_data_user(): void
    {
        $target = User::create([
            'users_nik'           => '7777777777777777',
            'users_username'      => 'user_target',
            'users_email'         => 'target@test.com',
            'users_password_hash' => 'password123',
            'users_jabatan'       => 'Lama',
            'users_nomor_telepon' => '08000000000',
            'users_role'          => 'admin_gudang',
        ]);

        // INPUT  : PUT /users/{id} dengan jabatan baru
        // EKSPEK : Redirect ke /users, jabatan berubah di DB
        $response = $this->actingAs($this->spv)->put("/users/{$target->users_id}", [
            'users_nik'           => '7777777777777777',
            'users_username'      => 'user_target',
            'users_email'         => 'target@test.com',
            'users_jabatan'       => 'Jabatan Baru',
            'users_nomor_telepon' => '08000000001',
            'users_role'          => 'admin_gudang',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['users_jabatan' => 'Jabatan Baru']);
    }

    /** TC-SPV-10: SPV dapat menghapus user lain (bukan diri sendiri) */
    public function test_spv_dapat_menghapus_user_lain(): void
    {
        $target = User::create([
            'users_nik'           => '6666666666666666',
            'users_username'      => 'user_akan_dihapus',
            'users_email'         => 'hapus@test.com',
            'users_password_hash' => 'password123',
            'users_jabatan'       => 'Tester',
            'users_nomor_telepon' => '08000000000',
            'users_role'          => 'admin_gudang',
        ]);

        // INPUT  : DELETE /users/{id}
        // EKSPEK : Redirect ke /users, user terhapus (soft delete)
        $response = $this->actingAs($this->spv)
            ->delete("/users/{$target->users_id}");

        $response->assertRedirect('/users');
        $this->assertSoftDeleted('users', ['users_id' => $target->users_id], null, 'users_deleted_at');
    }

    /** TC-SPV-11: SPV tidak dapat menghapus akunnya sendiri */
    public function test_spv_tidak_dapat_menghapus_akun_sendiri(): void
    {
        // INPUT  : DELETE /users/{id_diri_sendiri}
        // EKSPEK : Redirect kembali dengan pesan error, user TIDAK terhapus
        $response = $this->actingAs($this->spv)
            ->delete("/users/{$this->spv->users_id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['users_id' => $this->spv->users_id, 'users_deleted_at' => null]);
    }

    // =========================================================================
    // LAPORAN PERSEDIAAN
    // =========================================================================

    /** TC-SPV-12: Halaman laporan dapat diakses */
    public function test_halaman_laporan_dapat_diakses_oleh_spv(): void
    {
        // INPUT  : GET /laporan
        // EKSPEK : HTTP 200
        $this->actingAs($this->spv)
            ->get('/laporan')
            ->assertStatus(200);
    }

    /** TC-SPV-13: Filter laporan berdasarkan tanggal */
    public function test_laporan_dapat_difilter_berdasarkan_tanggal(): void
    {
        // INPUT  : GET /laporan?start_date=2024-01-01&end_date=2024-12-31
        // EKSPEK : HTTP 200 (filter diterima tanpa error)
        $this->actingAs($this->spv)
            ->get('/laporan?start_date=2024-01-01&end_date=2024-12-31&type=semua')
            ->assertStatus(200);
    }

    // =========================================================================
    // PERUSAHAAN TUJUAN
    // =========================================================================

    /** TC-SPV-14: Halaman daftar perusahaan tujuan dapat diakses */
    public function test_halaman_perusahaan_tujuan_dapat_diakses(): void
    {
        $this->actingAs($this->spv)
            ->get('/perusahaan-tujuan')
            ->assertStatus(200);
    }

    /** TC-SPV-15: SPV dapat menambah perusahaan tujuan */
    public function test_spv_dapat_menambah_perusahaan_tujuan(): void
    {
        // INPUT  : POST /perusahaan-tujuan dengan data valid
        // EKSPEK : Redirect ke /perusahaan-tujuan, data tersimpan
        $response = $this->actingAs($this->spv)->post('/perusahaan-tujuan', [
            'nama'   => 'PT Test Jaya',
            'kontak' => '08199988877',
            'alamat' => 'Jl. Test No. 1',
        ]);

        $response->assertRedirect('/perusahaan-tujuan');
        $this->assertDatabaseHas('perusahaan_tujuan', ['nama' => 'PT Test Jaya']);
    }

    /** TC-SPV-16: Gagal tambah perusahaan jika nama kosong */
    public function test_gagal_tambah_perusahaan_jika_nama_kosong(): void
    {
        // INPUT  : POST /perusahaan-tujuan tanpa nama
        // EKSPEK : Error validasi pada field nama
        $response = $this->actingAs($this->spv)->post('/perusahaan-tujuan', [
            'nama'   => '',
            'kontak' => '08199988877',
            'alamat' => 'Jl. Test No. 1',
        ]);

        $response->assertSessionHasErrors(['nama']);
    }

    // =========================================================================
    // NOTIFIKASI ROP
    // =========================================================================

    /** TC-SPV-17: Halaman notifikasi ROP dapat diakses */
    public function test_halaman_notifikasi_rop_dapat_diakses_oleh_spv(): void
    {
        $this->actingAs($this->spv)
            ->get('/notifikasi-rop')
            ->assertStatus(200);
    }

    // =========================================================================
    // LOG AKTIVITAS
    // =========================================================================

    /** TC-SPV-18: Halaman log aktivitas dapat diakses */
    public function test_halaman_log_aktivitas_dapat_diakses_oleh_spv(): void
    {
        $this->actingAs($this->spv)
            ->get('/activity-log')
            ->assertStatus(200);
    }
}
