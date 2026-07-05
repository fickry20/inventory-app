<?php

namespace Tests\Feature\BlackBox;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * BLACK BOX TEST — Profil Pengguna
 *
 * Menguji halaman profile dan pembaruan data profile / password
 * dari sudut pandang input/output luar (black box).
 */
class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'users_nik'           => '1234567890123456',
            'users_username'      => 'user_profile_test',
            'users_email'         => 'profile@test.com',
            'users_password_hash' => 'password123',
            'users_jabatan'       => 'Staf',
            'users_nomor_telepon' => '08123456789',
            'users_role'          => 'staf_inventory',
        ]);
    }

    /** TC-PRF-01: Halaman profil dapat diakses setelah login */
    public function test_halaman_profil_dapat_diakses(): void
    {
        // INPUT  : GET /profile (setelah login)
        // EKSPEK : HTTP 200, mengandung username user yang login
        $response = $this->actingAs($this->user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee($this->user->users_username);
    }

    /** TC-PRF-02: Memperbarui profil dengan data valid */
    public function test_memperbarui_profil_dengan_data_valid(): void
    {
        // INPUT  : PUT /profile dengan data baru yang valid
        // EKSPEK : Redirect ke /profile, pesan sukses, data berubah di DB
        $response = $this->actingAs($this->user)->put('/profile', [
            'users_nik'           => '1234567890123456',
            'users_username'      => 'profile_baru',
            'users_email'         => 'profilebaru@test.com',
            'users_nomor_telepon' => '08987654321',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'users_id'            => $this->user->users_id,
            'users_username'      => 'profile_baru',
            'users_email'         => 'profilebaru@test.com',
            'users_nomor_telepon' => '08987654321',
        ]);
    }

    /** TC-PRF-03: Gagal memperbarui profil jika email duplikat */
    public function test_gagal_memperbarui_profil_jika_email_duplikat(): void
    {
        // Buat user lain terlebih dahulu
        User::create([
            'users_nik'           => '9999999999999999',
            'users_username'      => 'user_lain',
            'users_email'         => 'lain@test.com',
            'users_password_hash' => 'password123',
            'users_jabatan'       => 'Staf',
            'users_nomor_telepon' => '08123456789',
            'users_role'          => 'admin_gudang',
        ]);

        // INPUT  : PUT /profile mencoba memakai email user lain
        // EKSPEK : Session memiliki error validasi email
        $response = $this->actingAs($this->user)->put('/profile', [
            'users_nik'           => '1234567890123456',
            'users_username'      => 'user_profile_test',
            'users_email'         => 'lain@test.com', // duplikat
            'users_nomor_telepon' => '08123456789',
        ]);

        $response->assertSessionHasErrors(['users_email']);
    }

    /** TC-PRF-04: Memperbarui password dengan password baru */
    public function test_memperbarui_password_berhasil(): void
    {
        // INPUT  : PUT /profile dengan password & password_confirmation
        // EKSPEK : Redirect ke /profile, password baru dapat digunakan untuk login
        $response = $this->actingAs($this->user)->put('/profile', [
            'users_nik'             => '1234567890123456',
            'users_username'        => 'user_profile_test',
            'users_email'           => 'profile@test.com',
            'users_nomor_telepon'   => '08123456789',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');

        // Logout dan coba login dengan password baru
        $this->post('/logout');
        $this->assertGuest();

        $loginResponse = $this->post('/login', [
            'users_email'         => 'profile@test.com',
            'users_password_hash' => 'newpassword123',
        ]);

        $loginResponse->assertRedirect();
        $this->assertAuthenticated();
    }
}
