<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Driver;
use App\Models\PerusahaanTujuan;
use App\Models\SukuCadang;
use App\Models\Kendaraan;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LogisticsModuleTest extends TestCase
{
    use RefreshDatabase;

    private $spv;
    private $staf;
    private $admin;
    private $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup users
        $this->spv = User::create([
            'users_nik' => '3201010101010003',
            'users_username' => 'spv_user',
            'users_email' => 'spv@example.com',
            'users_password_hash' => bcrypt('password123'),
            'users_role' => 'spv',
            'users_jabatan' => 'Supervisor',
            'users_nomor_telepon' => '081234567892',
        ]);

        $this->staf = User::create([
            'users_nik' => '3201010101010002',
            'users_username' => 'staf_inventory',
            'users_email' => 'staf.inventory@example.com',
            'users_password_hash' => bcrypt('password123'),
            'users_role' => 'staf_inventory',
            'users_jabatan' => 'Staf Inventory',
            'users_nomor_telepon' => '081234567891',
        ]);

        $this->admin = User::create([
            'users_nik' => '3201010101010001',
            'users_username' => 'admin_gudang',
            'users_email' => 'admin.gudang@example.com',
            'users_password_hash' => bcrypt('password123'),
            'users_role' => 'admin_gudang',
            'users_jabatan' => 'Admin Gudang',
            'users_nomor_telepon' => '081234567890',
        ]);

        // Setup supplier
        $this->supplier = Supplier::create([
            'supplier_nama' => 'PT. Global Spareparts',
            'supplier_kontak' => '081122334455',
            'supplier_alamat' => 'Kawasan Industri Jababeka, Bekasi',
        ]);
    }

    /**
     * Test Staf Inventory can manage Drivers under Suppliers.
     */
    public function test_staf_inventory_can_crud_drivers()
    {
        Storage::fake('public');

        // Create
        $response = $this->actingAs($this->staf)->post(route('drivers.store', $this->supplier->supplier_id), [
            'nama_driver' => 'Joko Susilo',
            'plat_kendaraan' => 'B 9999 DEF',
            'no_surat_jalan' => 'SJ-1002',
            'foto_sj' => UploadedFile::fake()->image('sj_photo.png'),
        ]);

        $response->assertRedirect(route('supplier.edit', $this->supplier->supplier_id));
        $this->assertDatabaseHas('drivers', [
            'nama_driver' => 'Joko Susilo',
            'plat_kendaraan' => 'B 9999 DEF',
            'no_surat_jalan' => 'SJ-1002',
        ]);

        $driver = Driver::first();
        $this->assertNotNull($driver->foto_sj);
        Storage::disk('public')->assertExists($driver->foto_sj);

        // Update
        $response = $this->actingAs($this->staf)->put(route('drivers.update', $driver->id), [
            'nama_driver' => 'Joko Susilo Purwanto',
            'plat_kendaraan' => 'B 8888 GHI',
            'no_surat_jalan' => 'SJ-1002-REV',
        ]);

        $response->assertRedirect(route('supplier.edit', $this->supplier->supplier_id));
        $this->assertDatabaseHas('drivers', [
            'id' => $driver->id,
            'nama_driver' => 'Joko Susilo Purwanto',
            'plat_kendaraan' => 'B 8888 GHI',
            'no_surat_jalan' => 'SJ-1002-REV',
        ]);

        // Delete
        $response = $this->actingAs($this->staf)->delete(route('drivers.destroy', $driver->id));
        $response->assertRedirect(route('supplier.edit', $this->supplier->supplier_id));
        $this->assertDatabaseMissing('drivers', ['id' => $driver->id]);
        Storage::disk('public')->assertMissing($driver->foto_sj);
    }

    /**
     * Test SPV can manage Perusahaan Tujuan (CRUD).
     */
    public function test_spv_can_crud_perusahaan_tujuan()
    {
        // Index
        $response = $this->actingAs($this->spv)->get(route('perusahaan-tujuan.index'));
        $response->assertStatus(200);

        // Create
        $response = $this->actingAs($this->spv)->post(route('perusahaan-tujuan.store'), [
            'nama' => 'PT. Maju Bersama',
            'kontak' => '081234567890',
            'alamat' => 'Jl. Industri No. 5',
        ]);

        $response->assertRedirect(route('perusahaan-tujuan.index'));
        $this->assertDatabaseHas('perusahaan_tujuan', [
            'nama' => 'PT. Maju Bersama',
            'kontak' => '081234567890',
            'alamat' => 'Jl. Industri No. 5',
        ]);

        $perusahaan = PerusahaanTujuan::first();

        // Update
        $response = $this->actingAs($this->spv)->put(route('perusahaan-tujuan.update', $perusahaan->id), [
            'nama' => 'PT. Maju Bersama Sejahtera',
            'kontak' => '081234567891',
            'alamat' => 'Jl. Industri No. 6',
        ]);

        $response->assertRedirect(route('perusahaan-tujuan.index'));
        $this->assertDatabaseHas('perusahaan_tujuan', [
            'id' => $perusahaan->id,
            'nama' => 'PT. Maju Bersama Sejahtera',
        ]);

        // Delete
        $response = $this->actingAs($this->spv)->delete(route('perusahaan-tujuan.destroy', $perusahaan->id));
        $response->assertRedirect(route('perusahaan-tujuan.index'));
        $this->assertSoftDeleted('perusahaan_tujuan', ['id' => $perusahaan->id]);
    }

    /**
     * Test API endpoint for driver selection.
     */
    public function test_api_can_fetch_drivers_by_supplier()
    {
        $driver = Driver::create([
            'supplier_id' => $this->supplier->supplier_id,
            'nama_driver' => 'Driver A',
            'plat_kendaraan' => 'B 1111 AAA',
            'no_surat_jalan' => 'SJ-1111',
        ]);

        $response = $this->actingAs($this->admin)->get(route('api.supplier.drivers', $this->supplier->supplier_id));
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'nama_driver' => 'Driver A',
            'no_surat_jalan' => 'SJ-1111',
        ]);
    }

    /**
     * Test Admin Gudang can create Transaksi Masuk and Transaksi Keluar.
     */
    public function test_admin_gudang_can_record_transactions()
    {
        $sukuCadang = SukuCadang::create([
            'suku_cadang_supplier_id' => $this->supplier->supplier_id,
            'suku_cadang_kode' => 'FL-OIL-001',
            'suku_cadang_nama' => 'Filter Oli Denso',
            'suku_cadang_kategori' => 'Filter',
            'suku_cadang_satuan' => 'Pcs',
            'suku_cadang_stok_total' => 0,
            'suku_cadang_reorder_point' => 10,
            'suku_cadang_stok_minimum' => 5,
        ]);

        $driver = Driver::create([
            'supplier_id' => $this->supplier->supplier_id,
            'nama_driver' => 'Budi',
            'plat_kendaraan' => 'B 1234 ABC',
            'no_surat_jalan' => 'SJ-SUP-123',
        ]);

        $perusahaan = PerusahaanTujuan::create([
            'nama' => 'PT. Tujuan Baru',
            'kontak' => '0822',
            'alamat' => 'Jl. Tujuan',
        ]);

        $kendaraan = Kendaraan::create([
            'kendaraan_plat' => 'B 9999 INTERNAL',
            'kendaraan_jenis' => 'box',
            'kendaraan_nama_driver' => 'Rudi',
        ]);

        // 1. Transaksi Masuk
        $response = $this->actingAs($this->admin)->post(route('transaksi-masuk.store'), [
            'transaksi_masuk_suku_cadang_id' => $sukuCadang->suku_cadang_id,
            'transaksi_masuk_supplier_id' => $this->supplier->supplier_id,
            'driver_id' => $driver->id,
            'transaksi_masuk_jumlah' => 100,
            'transaksi_masuk_keterangan' => 'Barang masuk oke',
        ]);

        $response->assertRedirect(route('transaksi-masuk.index'));
        $this->assertDatabaseHas('transaksi_masuk', [
            'driver_id' => $driver->id,
            'transaksi_masuk_no_surat_jalan' => 'SJ-SUP-123',
            'transaksi_masuk_jumlah' => 100,
        ]);

        // Verify stock incremented
        $sukuCadang->refresh();
        $this->assertEquals(100, $sukuCadang->suku_cadang_stok_total);

        // 2. Transaksi Keluar
        $response = $this->actingAs($this->admin)->post(route('transaksi-keluar.store'), [
            'suku_cadang_id' => $sukuCadang->suku_cadang_id,
            'kendaraan_id' => $kendaraan->kendaraan_id,
            'no_surat_jalan' => 'SJ-OUT-456',
            'tujuan_pt_id' => $perusahaan->id,
            'jumlah_diminta' => 30,
            'keterangan' => 'Kirim ke customer',
        ]);

        $response->assertRedirect(route('transaksi-keluar.index'));
        $this->assertDatabaseHas('transaksi_keluar', [
            'no_surat_jalan' => 'SJ-OUT-456',
            'tujuan_pt_id' => $perusahaan->id,
            'jumlah_diminta' => 30,
            'jumlah_terpenuhi' => 30,
            'status' => 'terpenuhi',
        ]);

        // Verify stock decremented
        $sukuCadang->refresh();
        $this->assertEquals(70, $sukuCadang->suku_cadang_stok_total);
    }

    /**
     * Test Cetak SJ print view is accessible by SPV.
     */
    public function test_spv_can_print_surat_jalan()
    {
        $sukuCadang = SukuCadang::create([
            'suku_cadang_supplier_id' => $this->supplier->supplier_id,
            'suku_cadang_kode' => 'FL-OIL-001',
            'suku_cadang_nama' => 'Filter Oli Denso',
            'suku_cadang_kategori' => 'Filter',
            'suku_cadang_satuan' => 'Pcs',
            'suku_cadang_stok_total' => 100,
            'suku_cadang_reorder_point' => 10,
            'suku_cadang_stok_minimum' => 5,
        ]);

        $perusahaan = PerusahaanTujuan::create([
            'nama' => 'PT. Tujuan Baru',
            'kontak' => '0822',
            'alamat' => 'Jl. Tujuan',
        ]);

        $kendaraan = Kendaraan::create([
            'kendaraan_plat' => 'B 9999 INTERNAL',
            'kendaraan_jenis' => 'box',
            'kendaraan_nama_driver' => 'Rudi',
        ]);

        $transaksi = TransaksiKeluar::create([
            'suku_cadang_id' => $sukuCadang->suku_cadang_id,
            'users' => $this->admin->users_id,
            'kendaraan_id' => $kendaraan->kendaraan_id,
            'no_surat_jalan' => 'SJ-OUT-999',
            'tujuan_pt_id' => $perusahaan->id,
            'jumlah_diminta' => 10,
            'jumlah_terpenuhi' => 10,
            'status' => 'terpenuhi',
        ]);

        $response = $this->actingAs($this->spv)->get(route('transaksi-keluar.cetak-sj', $transaksi->transaksi_keluar_id));
        $response->assertStatus(200);
        $response->assertSee('SJ-OUT-999');
        $response->assertSee('PT. Tujuan Baru');
    }

    /**
     * Test SPV can view transaction indexes but cannot write to them.
     */
    public function test_spv_can_view_transaction_indexes_but_cannot_modify_them()
    {
        $sukuCadang = SukuCadang::create([
            'suku_cadang_supplier_id' => $this->supplier->supplier_id,
            'suku_cadang_kode' => 'FL-OIL-002',
            'suku_cadang_nama' => 'Filter Oli Bosch',
            'suku_cadang_kategori' => 'Filter',
            'suku_cadang_satuan' => 'Pcs',
            'suku_cadang_stok_total' => 100,
            'suku_cadang_reorder_point' => 10,
            'suku_cadang_stok_minimum' => 5,
        ]);

        $driver = Driver::create([
            'supplier_id' => $this->supplier->supplier_id,
            'nama_driver' => 'Budi',
            'plat_kendaraan' => 'B 1234 ABC',
            'no_surat_jalan' => 'SJ-SUP-123',
        ]);

        // 1. SPV can access index listings
        $response = $this->actingAs($this->spv)->get(route('transaksi-masuk.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->spv)->get(route('transaksi-keluar.index'));
        $response->assertStatus(200);

        // 2. SPV cannot store a new Transaksi Masuk (403 Forbidden)
        $response = $this->actingAs($this->spv)->post(route('transaksi-masuk.store'), [
            'transaksi_masuk_suku_cadang_id' => $sukuCadang->suku_cadang_id,
            'transaksi_masuk_supplier_id' => $this->supplier->supplier_id,
            'driver_id' => $driver->id,
            'transaksi_masuk_jumlah' => 100,
        ]);
        $response->assertStatus(403);

        // 3. SPV cannot store a new Transaksi Keluar (403 Forbidden)
        $response = $this->actingAs($this->spv)->post(route('transaksi-keluar.store'), [
            'suku_cadang_id' => $sukuCadang->suku_cadang_id,
            'no_surat_jalan' => 'SJ-OUT-777',
            'jumlah_diminta' => 5,
        ]);
        $response->assertStatus(403);
    }
}
