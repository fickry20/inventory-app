<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test SPV user access.
     */
    public function test_spv_can_access_dashboard_and_laporan()
    {
        $spv = User::create([
            'users_nik' => '3201010101010003',
            'users_username' => 'spv_user',
            'users_email' => 'spv@example.com',
            'users_password_hash' => bcrypt('password123'),
            'users_role' => 'spv',
            'users_jabatan' => 'Supervisor',
            'users_nomor_telepon' => '081234567892',
        ]);

        $response = $this->actingAs($spv)->get('/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($spv)->get('/laporan');
        $response->assertStatus(200);
    }

    /**
     * Test staf inventory user access.
     */
    public function test_staf_inventory_cannot_access_dashboard_but_can_access_suku_cadang()
    {
        $staf = User::create([
            'users_nik' => '3201010101010002',
            'users_username' => 'staf_inventory',
            'users_email' => 'staf.inventory@example.com',
            'users_password_hash' => bcrypt('password123'),
            'users_role' => 'staf_inventory',
            'users_jabatan' => 'Staf Inventory',
            'users_nomor_telepon' => '081234567891',
        ]);

        $response = $this->actingAs($staf)->get('/dashboard');
        $response->assertStatus(403);

        $response = $this->actingAs($staf)->get('/suku-cadang');
        $response->assertStatus(200);
    }

    /**
     * Test admin gudang user access.
     */
    public function test_admin_gudang_cannot_access_suku_cadang_but_can_access_transaksi_masuk()
    {
        $admin = User::create([
            'users_nik' => '3201010101010001',
            'users_username' => 'admin_gudang',
            'users_email' => 'admin.gudang@example.com',
            'users_password_hash' => bcrypt('password123'),
            'users_role' => 'admin_gudang',
            'users_jabatan' => 'Admin Gudang',
            'users_nomor_telepon' => '081234567890',
        ]);

        $response = $this->actingAs($admin)->get('/suku-cadang');
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->get('/transaksi-masuk');
        $response->assertStatus(200);
    }
}
