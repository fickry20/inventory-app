<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SukuCadang;
use App\Models\Supplier;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    private $spv;
    private $staf;
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

        $this->supplier = Supplier::create([
            'supplier_nama' => 'PT. Global Spareparts',
            'supplier_kontak' => '081122334455',
            'supplier_alamat' => 'Kawasan Industri Jababeka, Bekasi',
        ]);
    }

    /**
     * Test login and logout logs.
     */
    public function test_login_and_logout_activities_are_logged()
    {
        // 1. Test Login logging
        $response = $this->post(route('login.post'), [
            'users_email' => 'spv@example.com',
            'users_password_hash' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('activity_logs', [
            'users_id' => $this->spv->users_id,
            'action' => 'LOGIN',
            'description' => 'Melakukan login ke sistem',
        ]);

        // 2. Test Logout logging
        $response = $this->actingAs($this->spv)->post(route('logout'));
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('activity_logs', [
            'users_id' => $this->spv->users_id,
            'action' => 'LOGOUT',
            'description' => 'Melakukan logout dari sistem',
        ]);
    }

    /**
     * Test profile update logs.
     */
    public function test_profile_update_is_logged()
    {
        $response = $this->actingAs($this->staf)->put(route('profile.update'), [
            'users_nik' => '3201010101010002',
            'users_username' => 'staf_inventory_revised',
            'users_email' => 'staf.inventory@example.com',
            'users_nomor_telepon' => '089999999999',
        ]);

        $response->assertRedirect(route('profile'));
        $this->assertDatabaseHas('activity_logs', [
            'users_id' => $this->staf->users_id,
            'action' => 'UPDATE_PROFILE',
            'description' => 'Memperbarui data profil / password',
        ]);
    }

    /**
     * Test CRUD on Suku Cadang generates logs.
     */
    public function test_suku_cadang_crud_activities_are_logged()
    {
        // 1. Create Suku Cadang
        $response = $this->actingAs($this->staf)->post(route('suku-cadang.store'), [
            'suku_cadang_supplier_id' => $this->supplier->supplier_id,
            'suku_cadang_kode' => 'FL-OIL-999',
            'suku_cadang_nama' => 'Filter Oli Super',
            'suku_cadang_kategori' => 'Filter',
            'suku_cadang_satuan' => 'Pcs',
            'suku_cadang_stok_total' => 20,
            'suku_cadang_reorder_point' => 5,
            'suku_cadang_stok_minimum' => 2,
        ]);

        $response->assertRedirect(route('suku-cadang.index'));
        $this->assertDatabaseHas('activity_logs', [
            'users_id' => $this->staf->users_id,
            'action' => 'CREATE_SUKU_CADANG',
        ]);

        $sukuCadang = SukuCadang::first();

        // 2. Update Suku Cadang
        $response = $this->actingAs($this->staf)->put(route('suku-cadang.update', $sukuCadang->suku_cadang_id), [
            'suku_cadang_supplier_id' => $this->supplier->supplier_id,
            'suku_cadang_kode' => 'FL-OIL-999',
            'suku_cadang_nama' => 'Filter Oli Super Revised',
            'suku_cadang_kategori' => 'Filter',
            'suku_cadang_satuan' => 'Pcs',
            'suku_cadang_stok_total' => 20,
            'suku_cadang_reorder_point' => 5,
            'suku_cadang_stok_minimum' => 2,
        ]);

        $response->assertRedirect(route('suku-cadang.index'));
        $this->assertDatabaseHas('activity_logs', [
            'users_id' => $this->staf->users_id,
            'action' => 'UPDATE_SUKU_CADANG',
            'description' => 'Mengubah data suku cadang: Filter Oli Super Revised (FL-OIL-999)',
        ]);

        // 3. Delete Suku Cadang
        $response = $this->actingAs($this->staf)->delete(route('suku-cadang.destroy', $sukuCadang->suku_cadang_id));
        $response->assertRedirect(route('suku-cadang.index'));
        $this->assertDatabaseHas('activity_logs', [
            'users_id' => $this->staf->users_id,
            'action' => 'DELETE_SUKU_CADANG',
            'description' => 'Menghapus suku cadang: Filter Oli Super Revised (FL-OIL-999)',
        ]);
    }

    /**
     * Test role-based visibility of activity logs.
     */
    public function test_activity_log_visibility_restrictions()
    {
        // Create some logs manually
        ActivityLog::create([
            'users_id' => $this->spv->users_id,
            'users_username' => $this->spv->users_username,
            'users_role' => $this->spv->users_role,
            'action' => 'LOGIN',
            'description' => 'Melakukan login ke sistem',
        ]);

        ActivityLog::create([
            'users_id' => $this->staf->users_id,
            'users_username' => $this->staf->users_username,
            'users_role' => $this->staf->users_role,
            'action' => 'LOGIN',
            'description' => 'Melakukan login ke sistem',
        ]);

        // 1. SPV can see all logs
        $response = $this->actingAs($this->spv)->get(route('activity-log.index'));
        $response->assertStatus(200);
        $response->assertSee('spv_user');
        $response->assertSee('staf_inventory');

        // 2. Staf Inventory can only see their own logs
        $response = $this->actingAs($this->staf)->get(route('activity-log.index'));
        $response->assertStatus(200);
        $response->assertDontSee('spv_user');
        $response->assertSee('staf_inventory');
    }
}
