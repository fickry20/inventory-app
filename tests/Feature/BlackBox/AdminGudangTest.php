<?php

namespace Tests\Feature\BlackBox;

use Tests\TestCase;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Kendaraan;
use App\Models\SukuCadang;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * BLACK BOX TEST — Fitur Role Admin Gudang
 *
 * Menguji semua halaman dan aksi yang dapat dilakukan oleh role admin_gudang:
 * Transaksi Masuk, Transaksi Keluar, Notifikasi ROP, Log Aktivitas.
 */
class AdminGudangTest extends TestCase
{
    use RefreshDatabase;

    private User $adminGudang;
    private SukuCadang $sukuCadang;
    private Supplier $supplier;
    private Kendaraan $kendaraan;
    private Driver $driver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminGudang = User::create([
            'users_nik'           => '3201010101010001',
            'users_username'      => 'admin_gudang_test',
            'users_email'         => 'admin.gudang@test.com',
            'users_password_hash' => 'password123',
            'users_jabatan'       => 'Admin Gudang',
            'users_nomor_telepon' => '081234567890',
            'users_role'          => 'admin_gudang',
        ]);

        $this->supplier = Supplier::create([
            'supplier_nama'   => 'PT Supplier Test',
            'supplier_kontak' => '08111222333',
            'supplier_alamat' => 'Jl. Supplier No. 1',
        ]);

        $this->sukuCadang = SukuCadang::create([
            'suku_cadang_supplier_id'   => $this->supplier->supplier_id,
            'suku_cadang_kode'          => 'SC-TEST-001',
            'suku_cadang_nama'          => 'Filter Oli Test',
            'suku_cadang_kategori'      => 'Filter',
            'suku_cadang_satuan'        => 'Pcs',
            'suku_cadang_stok_total'    => 100,
            'suku_cadang_reorder_point' => 20,
            'suku_cadang_stok_minimum'  => 10,
        ]);

        $this->kendaraan = Kendaraan::create([
            'kendaraan_plat'        => 'B 9999 TEST',
            'kendaraan_nama_driver' => 'Driver Test',
            'kendaraan_jenis'       => 'box',
            'kendaraan_no_telepon'  => '08111222444',
        ]);

        $this->driver = Driver::create([
            'supplier_id'    => $this->supplier->supplier_id,
            'nama_driver'    => 'Driver Masuk Test',
            'plat_kendaraan' => 'B 8888 TST',
            'no_surat_jalan' => 'SJ-' . rand(1000, 9999),
        ]);
    }

    // =========================================================================
    // TRANSAKSI MASUK — HALAMAN
    // =========================================================================

    /** TC-ADM-01: Halaman daftar transaksi masuk dapat diakses */
    public function test_halaman_transaksi_masuk_dapat_diakses(): void
    {
        // INPUT  : GET /transaksi-masuk
        // EKSPEK : HTTP 200
        $this->actingAs($this->adminGudang)
            ->get('/transaksi-masuk')
            ->assertStatus(200);
    }

    /** TC-ADM-02: Halaman form catat barang masuk dapat diakses */
    public function test_halaman_form_catat_barang_masuk_dapat_diakses(): void
    {
        // INPUT  : GET /transaksi-masuk/create
        // EKSPEK : HTTP 200
        $this->actingAs($this->adminGudang)
            ->get('/transaksi-masuk/create')
            ->assertStatus(200);
    }

    // =========================================================================
    // TRANSAKSI MASUK — STORE
    // =========================================================================

    /** TC-ADM-03: Admin gudang dapat mencatat transaksi masuk */
    public function test_admin_gudang_dapat_mencatat_transaksi_masuk(): void
    {
        // INPUT  : POST /transaksi-masuk dengan data valid
        // EKSPEK : Redirect ke /transaksi-masuk, stok suku cadang bertambah
        $stokAwal = $this->sukuCadang->suku_cadang_stok_total;

        $response = $this->actingAs($this->adminGudang)->post('/transaksi-masuk', [
            'transaksi_masuk_suku_cadang_id' => $this->sukuCadang->suku_cadang_id,
            'transaksi_masuk_supplier_id'    => $this->supplier->supplier_id,
            'driver_id'                      => $this->driver->id,
            'transaksi_masuk_jumlah'         => 50,
            'transaksi_masuk_keterangan'     => 'Test masuk barang',
        ]);

        $response->assertRedirect('/transaksi-masuk');

        // Verifikasi stok bertambah
        $this->sukuCadang->refresh();
        $this->assertEquals($stokAwal + 50, $this->sukuCadang->suku_cadang_stok_total);
    }

    /** TC-ADM-04: Gagal catat transaksi masuk jika jumlah 0 */
    public function test_gagal_catat_transaksi_masuk_jika_jumlah_nol(): void
    {
        // INPUT  : POST /transaksi-masuk dengan jumlah = 0
        // EKSPEK : Error validasi pada field transaksi_masuk_jumlah
        $response = $this->actingAs($this->adminGudang)->post('/transaksi-masuk', [
            'transaksi_masuk_suku_cadang_id' => $this->sukuCadang->suku_cadang_id,
            'transaksi_masuk_supplier_id'    => $this->supplier->supplier_id,
            'driver_id'                      => $this->driver->id,
            'transaksi_masuk_jumlah'         => 0,
        ]);

        $response->assertSessionHasErrors(['transaksi_masuk_jumlah']);
    }

    /** TC-ADM-05: Gagal catat transaksi masuk tanpa suku cadang */
    public function test_gagal_catat_transaksi_masuk_tanpa_suku_cadang(): void
    {
        // INPUT  : POST /transaksi-masuk tanpa suku_cadang_id
        // EKSPEK : Error validasi pada field transaksi_masuk_suku_cadang_id
        $response = $this->actingAs($this->adminGudang)->post('/transaksi-masuk', [
            'transaksi_masuk_suku_cadang_id' => '',
            'transaksi_masuk_supplier_id'    => $this->supplier->supplier_id,
            'driver_id'                      => $this->driver->id,
            'transaksi_masuk_jumlah'         => 10,
        ]);

        $response->assertSessionHasErrors(['transaksi_masuk_suku_cadang_id']);
    }

    // =========================================================================
    // TRANSAKSI KELUAR — HALAMAN
    // =========================================================================

    /** TC-ADM-06: Halaman daftar transaksi keluar dapat diakses */
    public function test_halaman_transaksi_keluar_dapat_diakses(): void
    {
        // INPUT  : GET /transaksi-keluar
        // EKSPEK : HTTP 200
        $this->actingAs($this->adminGudang)
            ->get('/transaksi-keluar')
            ->assertStatus(200);
    }

    /** TC-ADM-07: Halaman form catat barang keluar dapat diakses */
    public function test_halaman_form_catat_barang_keluar_dapat_diakses(): void
    {
        // INPUT  : GET /transaksi-keluar/create
        // EKSPEK : HTTP 200
        $this->actingAs($this->adminGudang)
            ->get('/transaksi-keluar/create')
            ->assertStatus(200);
    }

    // =========================================================================
    // SEARCH TRANSAKSI
    // =========================================================================

    /** TC-ADM-08: Fitur pencarian transaksi masuk berfungsi */
    public function test_pencarian_transaksi_masuk_berfungsi(): void
    {
        // INPUT  : GET /transaksi-masuk?search=SJ-
        // EKSPEK : HTTP 200, tidak error
        $this->actingAs($this->adminGudang)
            ->get('/transaksi-masuk?search=SJ-TEST')
            ->assertStatus(200);
    }

    /** TC-ADM-09: Fitur pencarian transaksi keluar berfungsi */
    public function test_pencarian_transaksi_keluar_berfungsi(): void
    {
        // INPUT  : GET /transaksi-keluar?search=test
        // EKSPEK : HTTP 200, tidak error
        $this->actingAs($this->adminGudang)
            ->get('/transaksi-keluar?search=test')
            ->assertStatus(200);
    }

    // =========================================================================
    // NOTIFIKASI ROP & LOG AKTIVITAS
    // =========================================================================

    /** TC-ADM-10: Halaman notifikasi ROP dapat diakses */
    public function test_halaman_notifikasi_rop_dapat_diakses_oleh_admin_gudang(): void
    {
        // INPUT  : GET /notifikasi-rop
        // EKSPEK : HTTP 200
        $this->actingAs($this->adminGudang)
            ->get('/notifikasi-rop')
            ->assertStatus(200);
    }

    /** TC-ADM-11: Halaman log aktivitas dapat diakses */
    public function test_halaman_log_aktivitas_dapat_diakses_oleh_admin_gudang(): void
    {
        // INPUT  : GET /activity-log
        // EKSPEK : HTTP 200
        $this->actingAs($this->adminGudang)
            ->get('/activity-log')
            ->assertStatus(200);
    }
}
