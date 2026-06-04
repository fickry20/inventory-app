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
        Schema::create('drivers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supplier_id');
            $table->string('nama_driver', 150);
            $table->string('plat_kendaraan', 20);
            $table->string('no_surat_jalan', 100);
            $table->string('foto_sj', 255)->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')
                  ->references('supplier_id')
                  ->on('supplier')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
