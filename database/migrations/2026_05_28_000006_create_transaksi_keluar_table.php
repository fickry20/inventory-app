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
        Schema::create('transaksi_keluar', function (Blueprint $table) {
            $table->increments('transaksi_keluar_id');
            $table->unsignedInteger('suku_cadang_id');
            $table->unsignedInteger('users');
            $table->unsignedInteger('kendaraan_id');
            $table->string('no_surat_jalan', 100);
            $table->unsignedInteger('tujuan_pt_id');
            $table->integer('jumlah_diminta');
            $table->integer('jumlah_terpenuhi');
            $table->enum('status', ['terpenuhi', 'sebagian', 'ditolak']);
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('suku_cadang_id')
                  ->references('suku_cadang_id')
                  ->on('suku_cadang')
                  ->onDelete('cascade');

            $table->foreign('users')
                  ->references('users_id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('kendaraan_id')
                  ->references('kendaraan_id')
                  ->on('kendaraan')
                  ->onDelete('cascade');

            $table->foreign('tujuan_pt_id')
                  ->references('id')
                  ->on('perusahaan_tujuan')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_keluar');
    }
};
