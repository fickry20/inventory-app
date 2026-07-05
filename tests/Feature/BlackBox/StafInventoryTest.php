<?php

namespace Tests\Feature\BlackBox;

use Tests\TestCase;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Kendaraan;
use App\Models\SukuCadang;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * BLACK BOX TEST — Fitur Role Staf Inventory
 *
 * Menguji semua halaman dan aksi yang dapat dilakukan oleh role staf_inventory:
 * Supplier, Kendaraan, Suku Cadang, Notifikasi ROP, Log Aktivitas.
 */
class StafInventoryTest extends TestCase
{
    use RefreshDatabase;

    private User $staf;
    private Supplier $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->staf = User::create([
            'users_nik'           => '3201010101010002',
            'users_username'      => 'staf_test',
            'users_email'         => 'staf@test.com',
            'users_password_hash' => 'password123',
            'users_jabatan'       => 'Staf Inventory',
            'users_nomor_telepon' => '081234567891',
            'users_role'          => 'staf_inventory',
        ]);

        $this->supplier = Supplier::create([
            'supplier_nama'   => 'PT Supplier Test',
            'supplier_kontak' => '08111222333',
            'supplier_alamat' => 'Jl. Supplier No. 1',
        ]);
    }

    // =========================================================================
    // SUPPLIER
    // =========================================================================

    /** TC-STF-01: Halaman daftar supplier dapat diakses */
    public function test_halaman_daftar_supplier_dapat_diakses(): void
    {
        // INPUT  : GET /supplier
        // EKSPEK : HTTP 200
        $this->actingAs($this->staf)
            ->get('/supplier')
            ->assertStatus(200);
    }

    /** TC-STF-02: Halaman form tambah supplier dapat diakses */
    public function test_halaman_form_tambah_supplier_dapat_diakses(): void
    {
        // INPUT  : GET /supplier/create
        // EKSPEK : HTTP 200
        $this->actingAs($this->staf)
            ->get('/supplier/create')
            ->assertStatus(200);
    }

    /** TC-STF-03: Staf dapat menambah supplier baru */
    public function test_staf_dapat_menambah_supplier_baru(): void
    {
        // INPUT  : POST /supplier dengan data valid
        // EKSPEK : Redirect ke /supplier, data tersimpan di DB
        $response = $this->actingAs($this->staf)->post('/supplier', [
            'supplier_nama'   => 'PT Maju Mundur',
            'supplier_kontak' => '08199988866',
            'supplier_alamat' => 'Jl. Industri No. 5',
        ]);

        $response->assertRedirect('/supplier');
        $this->assertDatabaseHas('supplier', ['supplier_nama' => 'PT Maju Mundur']);
    }

    /** TC-STF-04: Gagal tambah supplier jika nama kosong */
    public function test_gagal_tambah_supplier_jika_nama_kosong(): void
    {
        // INPUT  : POST /supplier tanpa nama
        // EKSPEK : Error validasi pada field supplier_nama
        $response = $this->actingAs($this->staf)->post('/supplier', [
            'supplier_nama'   => '',
            'supplier_kontak' => '08199988866',
            'supplier_alamat' => 'Jl. Industri No. 5',
        ]);

        $response->assertSessionHasErrors(['supplier_nama']);
    }

    /** TC-STF-05: Staf dapat mengedit data supplier */
    public function test_staf_dapat_mengedit_data_supplier(): void
    {
        // INPUT  : PUT /supplier/{id} dengan nama baru
        // EKSPEK : Redirect ke /supplier, nama berubah di DB
        $response = $this->actingAs($this->staf)
            ->put("/supplier/{$this->supplier->supplier_id}", [
                'supplier_nama'   => 'PT Supplier Test UPDATED',
                'supplier_kontak' => '08111222333',
                'supplier_alamat' => 'Jl. Supplier No. 1',
            ]);

        $response->assertRedirect('/supplier');
        $this->assertDatabaseHas('supplier', ['supplier_nama' => 'PT Supplier Test UPDATED']);
    }

    /** TC-STF-06: Staf dapat menghapus supplier */
    public function test_staf_dapat_menghapus_supplier(): void
    {
        // INPUT  : DELETE /supplier/{id}
        // EKSPEK : Redirect ke /supplier, data tidak ada di DB
        $response = $this->actingAs($this->staf)
            ->delete("/supplier/{$this->supplier->supplier_id}");

        $response->assertRedirect('/supplier');
        $this->assertSoftDeleted('supplier', ['supplier_id' => $this->supplier->supplier_id], null, 'supplier_deleted_at');
    }

    // =========================================================================
    // KENDARAAN
    // =========================================================================

    /** TC-STF-07: Halaman daftar kendaraan dapat diakses */
    public function test_halaman_daftar_kendaraan_dapat_diakses(): void
    {
        // INPUT  : GET /kendaraan
        // EKSPEK : HTTP 200
        $this->actingAs($this->staf)
            ->get('/kendaraan')
            ->assertStatus(200);
    }

    /** TC-STF-08: Staf dapat menambah kendaraan baru */
    public function test_staf_dapat_menambah_kendaraan(): void
    {
        // INPUT  : POST /kendaraan dengan data valid
        // EKSPEK : Redirect ke /kendaraan, data tersimpan
        $response = $this->actingAs($this->staf)->post('/kendaraan', [
            'kendaraan_plat'         => 'B 1234 TEST',
            'kendaraan_nama_driver'  => 'Driver Test',
            'kendaraan_jenis'        => 'box',
            'kendaraan_no_telepon'   => '08111222555',
        ]);

        $response->assertRedirect('/kendaraan');
        $this->assertDatabaseHas('kendaraan', ['kendaraan_plat' => 'B 1234 TEST']);
    }

    /** TC-STF-09: Gagal tambah kendaraan jika plat kosong */
    public function test_gagal_tambah_kendaraan_jika_plat_kosong(): void
    {
        // INPUT  : POST /kendaraan tanpa plat nomor
        // EKSPEK : Error validasi
        $response = $this->actingAs($this->staf)->post('/kendaraan', [
            'kendaraan_plat'         => '',
            'kendaraan_nama_driver'  => 'Driver Test',
            'kendaraan_jenis'        => 'box',
            'kendaraan_no_telepon'   => '08111222555',
        ]);

        $response->assertSessionHasErrors(['kendaraan_plat']);
    }

    // =========================================================================
    // SUKU CADANG
    // =========================================================================

    /** TC-STF-10: Halaman daftar suku cadang dapat diakses */
    public function test_halaman_daftar_suku_cadang_dapat_diakses(): void
    {
        // INPUT  : GET /suku-cadang
        // EKSPEK : HTTP 200
        $this->actingAs($this->staf)
            ->get('/suku-cadang')
            ->assertStatus(200);
    }

    /** TC-STF-11: Halaman form tambah suku cadang dapat diakses */
    public function test_halaman_form_tambah_suku_cadang_dapat_diakses(): void
    {
        // INPUT  : GET /suku-cadang/create
        // EKSPEK : HTTP 200
        $this->actingAs($this->staf)
            ->get('/suku-cadang/create')
            ->assertStatus(200);
    }

    /** TC-STF-12: Staf dapat menambah suku cadang baru */
    public function test_staf_dapat_menambah_suku_cadang(): void
    {
        // INPUT  : POST /suku-cadang dengan data lengkap dan valid
        // EKSPEK : Redirect ke /suku-cadang, data tersimpan
        $response = $this->actingAs($this->staf)->post('/suku-cadang', [
            'suku_cadang_supplier_id'   => $this->supplier->supplier_id,
            'suku_cadang_kode'          => 'SC-TEST-001',
            'suku_cadang_nama'          => 'Oli Mesin Test',
            'suku_cadang_kategori'      => 'Pelumas',
            'suku_cadang_satuan'        => 'Liter',
            'suku_cadang_stok_total'    => 100,
            'suku_cadang_reorder_point' => 20,
            'suku_cadang_stok_minimum'  => 10,
        ]);

        $response->assertRedirect('/suku-cadang');
        $this->assertDatabaseHas('suku_cadang', ['suku_cadang_kode' => 'SC-TEST-001']);
    }

    /** TC-STF-13: Gagal tambah suku cadang jika kode duplikat */
    public function test_gagal_tambah_suku_cadang_jika_kode_duplikat(): void
    {
        // Buat suku cadang pertama
        SukuCadang::create([
            'suku_cadang_supplier_id'   => $this->supplier->supplier_id,
            'suku_cadang_kode'          => 'SC-DUPLIKAT-001',
            'suku_cadang_nama'          => 'Barang Pertama',
            'suku_cadang_kategori'      => 'Pelumas',
            'suku_cadang_satuan'        => 'Liter',
            'suku_cadang_stok_total'    => 50,
            'suku_cadang_reorder_point' => 10,
            'suku_cadang_stok_minimum'  => 5,
        ]);

        // INPUT  : POST /suku-cadang dengan kode yang sudah ada
        // EKSPEK : Error validasi pada field suku_cadang_kode
        $response = $this->actingAs($this->staf)->post('/suku-cadang', [
            'suku_cadang_supplier_id'   => $this->supplier->supplier_id,
            'suku_cadang_kode'          => 'SC-DUPLIKAT-001',
            'suku_cadang_nama'          => 'Barang Kedua',
            'suku_cadang_kategori'      => 'Pelumas',
            'suku_cadang_satuan'        => 'Liter',
            'suku_cadang_stok_total'    => 50,
            'suku_cadang_reorder_point' => 10,
            'suku_cadang_stok_minimum'  => 5,
        ]);

        $response->assertSessionHasErrors(['suku_cadang_kode']);
    }

    /** TC-STF-14: Staf dapat mengedit suku cadang */
    public function test_staf_dapat_mengedit_suku_cadang(): void
    {
        $sc = SukuCadang::create([
            'suku_cadang_supplier_id'   => $this->supplier->supplier_id,
            'suku_cadang_kode'          => 'SC-EDIT-001',
            'suku_cadang_nama'          => 'Nama Lama',
            'suku_cadang_kategori'      => 'Filter',
            'suku_cadang_satuan'        => 'Pcs',
            'suku_cadang_stok_total'    => 50,
            'suku_cadang_reorder_point' => 10,
            'suku_cadang_stok_minimum'  => 5,
        ]);

        // INPUT  : PUT /suku-cadang/{id} dengan nama baru
        // EKSPEK : Redirect ke /suku-cadang, nama berubah
        $response = $this->actingAs($this->staf)->put("/suku-cadang/{$sc->suku_cadang_id}", [
            'suku_cadang_supplier_id'   => $this->supplier->supplier_id,
            'suku_cadang_kode'          => 'SC-EDIT-001',
            'suku_cadang_nama'          => 'Nama Baru UPDATED',
            'suku_cadang_kategori'      => 'Filter',
            'suku_cadang_satuan'        => 'Pcs',
            'suku_cadang_stok_total'    => 50,
            'suku_cadang_reorder_point' => 10,
            'suku_cadang_stok_minimum'  => 5,
        ]);

        $response->assertRedirect('/suku-cadang');
        $this->assertDatabaseHas('suku_cadang', ['suku_cadang_nama' => 'Nama Baru UPDATED']);
    }

    /** TC-STF-15: Staf dapat menghapus suku cadang */
    public function test_staf_dapat_menghapus_suku_cadang(): void
    {
        $sc = SukuCadang::create([
            'suku_cadang_supplier_id'   => $this->supplier->supplier_id,
            'suku_cadang_kode'          => 'SC-HAPUS-001',
            'suku_cadang_nama'          => 'Barang Dihapus',
            'suku_cadang_kategori'      => 'Filter',
            'suku_cadang_satuan'        => 'Pcs',
            'suku_cadang_stok_total'    => 0,
            'suku_cadang_reorder_point' => 5,
            'suku_cadang_stok_minimum'  => 2,
        ]);

        // INPUT  : DELETE /suku-cadang/{id}
        // EKSPEK : Redirect ke /suku-cadang
        $response = $this->actingAs($this->staf)
            ->delete("/suku-cadang/{$sc->suku_cadang_id}");

        $response->assertRedirect('/suku-cadang');
    }

    // =========================================================================
    // NOTIFIKASI ROP & LOG AKTIVITAS
    // =========================================================================

    /** TC-STF-16: Halaman notifikasi ROP dapat diakses */
    public function test_halaman_notifikasi_rop_dapat_diakses_oleh_staf(): void
    {
        $this->actingAs($this->staf)
            ->get('/notifikasi-rop')
            ->assertStatus(200);
    }

    /** TC-STF-17: Halaman log aktivitas dapat diakses */
    public function test_halaman_log_aktivitas_dapat_diakses_oleh_staf(): void
    {
        $this->actingAs($this->staf)
            ->get('/activity-log')
            ->assertStatus(200);
    }

    // =========================================================================
    // SEARCH
    // =========================================================================

    /** TC-STF-18: Fitur pencarian supplier berfungsi */
    public function test_pencarian_supplier_mengembalikan_hasil(): void
    {
        // INPUT  : GET /supplier?search=PT+Supplier+Test
        // EKSPEK : HTTP 200, konten yang dicari tampil
        $this->actingAs($this->staf)
            ->get('/supplier?search=PT+Supplier+Test')
            ->assertStatus(200)
            ->assertSee('PT Supplier Test');
    }

    /** TC-STF-19: Pencarian dengan keyword kosong menampilkan semua data */
    public function test_pencarian_dengan_keyword_kosong_menampilkan_semua(): void
    {
        // INPUT  : GET /suku-cadang?search=
        // EKSPEK : HTTP 200 (tidak error)
        $this->actingAs($this->staf)
            ->get('/suku-cadang?search=')
            ->assertStatus(200);
    }
}
