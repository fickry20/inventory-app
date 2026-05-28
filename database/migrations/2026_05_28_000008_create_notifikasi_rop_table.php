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
        Schema::create('notifikasi_rop', function (Blueprint $table) {
            $table->increments('notifikasi_rop_id');
            $table->unsignedInteger('suku_cadang_id');
            $table->integer('rop_stok_saat_notif');
            $table->integer('rop_rop_saat_notif');
            $table->boolean('rop_sudah_ditangani')->default(false);
            $table->timestamp('rop_created_at')->nullable();
            $table->timestamp('rop_updated_at')->nullable();
            $table->timestamp('rop_deleted_at')->nullable();

            $table->foreign('suku_cadang_id')
                  ->references('suku_cadang_id')
                  ->on('suku_cadang')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_rop');
    }
};
