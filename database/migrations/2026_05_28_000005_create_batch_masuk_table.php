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
        Schema::create('batch_masuk', function (Blueprint $table) {
            $table->increments('batch_masuk_id');
            $table->unsignedInteger('suku_cadang_id');
            $table->unsignedInteger('transaksi_masuk');
            $table->integer('jumlah_awal');
            $table->integer('jumlah_sisa');
            $table->timestamp('tanggal_masuk');
            $table->boolean('is_habis')->default(false);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('suku_cadang_id')
                  ->references('suku_cadang_id')
                  ->on('suku_cadang')
                  ->onDelete('cascade');

            $table->foreign('transaksi_masuk')
                  ->references('transaksi_masuk_id')
                  ->on('transaksi_masuk')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_masuk');
    }
};
