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
        Schema::create('detail_keluar_fifo', function (Blueprint $table) {
            $table->increments('detail_keluar_fifo_id');
            $table->unsignedInteger('transaksi_keluar_id');
            $table->unsignedInteger('batch_masuk_id');
            $table->integer('fifo_jumlah_diambil');
            $table->timestamp('fifo_created_at')->nullable();
            $table->timestamp('fifo_updated_at')->nullable();
            $table->timestamp('fifo_deleted_at')->nullable();

            $table->foreign('transaksi_keluar_id')
                  ->references('transaksi_keluar_id')
                  ->on('transaksi_keluar')
                  ->onDelete('cascade');

            $table->foreign('batch_masuk_id')
                  ->references('batch_masuk_id')
                  ->on('batch_masuk')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_keluar_fifo');
    }
};
