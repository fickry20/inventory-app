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
        Schema::create('suku_cadang', function (Blueprint $table) {
            $table->increments('suku_cadang_id');
            $table->unsignedInteger('suku_cadang_supplier_id');
            $table->string('suku_cadang_kode', 50)->unique();
            $table->string('suku_cadang_nama', 150);
            $table->string('suku_cadang_kategori', 100);
            $table->string('suku_cadang_satuan', 20);
            $table->integer('suku_cadang_stok_total');
            $table->integer('suku_cadang_reorder_point');
            $table->integer('suku_cadang_stok_minimum');
            $table->timestamp('suku_cadang_created_at')->nullable();
            $table->timestamp('suku_cadang_updated_at')->nullable();
            $table->timestamp('suku_cadang_deleted_at')->nullable();

            $table->foreign('suku_cadang_supplier_id')
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
        Schema::dropIfExists('suku_cadang');
    }
};
