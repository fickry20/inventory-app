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
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->increments('kendaraan_id');
            $table->string('kendaraan_plat', 20)->unique();
            $table->enum('kendaraan_jenis', ['box', 'engkel']);
            $table->string('kendaraan_nama_driver', 150);
            $table->timestamp('kendaraan_created_at')->nullable();
            $table->timestamp('kendaraan_updated_at')->nullable();
            $table->timestamp('kendaraan_deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
