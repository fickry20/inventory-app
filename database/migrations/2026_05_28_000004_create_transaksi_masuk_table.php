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
        Schema::create('transaksi_masuk', function (Blueprint $table) {
            $table->increments('transaksi_masuk_id');
            $table->unsignedInteger('transaksi_masuk_suku_cadang_id');
            $table->unsignedInteger('transaksi_masuk_supplier_id');
            $table->unsignedInteger('transaksi_masuk_users_id');
            $table->unsignedInteger('transaksi_masuk_kendaraan_id')->nullable();
            $table->string('transaksi_masuk_no_dokumen', 100);
            $table->string('transaksi_masuk_no_surat_jalan', 100);
            $table->integer('transaksi_masuk_jumlah');
            $table->text('transaksi_masuk_keterangan')->nullable();
            $table->timestamp('transaksi_masuk_created_at')->nullable();
            $table->timestamp('transaksi_masuk_updated_at')->nullable();
            $table->timestamp('transaksi_masuk_deleted_at')->nullable();

            $table->foreign('transaksi_masuk_suku_cadang_id')
                  ->references('suku_cadang_id')
                  ->on('suku_cadang')
                  ->onDelete('cascade');

            $table->foreign('transaksi_masuk_supplier_id')
                  ->references('supplier_id')
                  ->on('supplier')
                  ->onDelete('cascade');

            $table->foreign('transaksi_masuk_users_id')
                  ->references('users_id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('transaksi_masuk_kendaraan_id')
                  ->references('kendaraan_id')
                  ->on('kendaraan')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_masuk');
    }
};
