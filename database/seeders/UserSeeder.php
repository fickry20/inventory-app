<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'users_nik'             => '3201010101010001',
                'users_username'        => 'admin_gudang',
                'users_email'           => 'admin.gudang@example.com',
                'users_password_hash'   => Hash::make('password123'),
                'users_jabatan'         => 'Admin Gudang',
                'users_nomor_telepon'   => '081234567890',
                'users_role'            => 'admin_gudang',
                'users_created_at'      => Carbon::now(),
                'users_updated_at'      => Carbon::now(),
            ],
            [
                'users_nik'             => '3201010101010002',
                'users_username'        => 'staf_inventory',
                'users_email'           => 'staf.inventory@example.com',
                'users_password_hash'   => Hash::make('password123'),
                'users_jabatan'         => 'Staf Inventory',
                'users_nomor_telepon'   => '081234567891',
                'users_role'            => 'staf_inventory',
                'users_created_at'      => Carbon::now(),
                'users_updated_at'      => Carbon::now(),
            ],
            [
                'users_nik'             => '3201010101010003',
                'users_username'        => 'spv_user',
                'users_email'           => 'spv@example.com',
                'users_password_hash'   => Hash::make('password123'),
                'users_jabatan'         => 'Supervisor',
                'users_nomor_telepon'   => '081234567892',
                'users_role'            => 'spv',
                'users_created_at'      => Carbon::now(),
                'users_updated_at'      => Carbon::now(),
            ],
        ]);
    }
}