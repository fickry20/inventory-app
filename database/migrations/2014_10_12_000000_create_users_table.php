<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('users_id');
            $table->string('users_nik', 20)->unique();
            $table->string('users_username', 100)->unique();
            $table->string('users_email', 150)->unique();
            $table->string('users_password_hash', 255);
            $table->string('users_jabatan', 100);
            $table->string('users_nomor_telepon', 20);
            $table->enum('users_role', ['admin_gudang', 'staf_inventory', 'spv']);
            $table->timestamp('users_created_at')->nullable();
            $table->timestamp('users_updated_at')->nullable();
            $table->timestamp('users_deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
