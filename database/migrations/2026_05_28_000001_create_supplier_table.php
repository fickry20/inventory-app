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
        Schema::create('supplier', function (Blueprint $table) {
            $table->increments('supplier_id');
            $table->string('supplier_nama', 150);
            $table->string('supplier_kontak', 100);
            $table->text('supplier_alamat');
            $table->timestamp('supplier_created_at')->nullable();
            $table->timestamp('supplier_updated_at')->nullable();
            $table->timestamp('supplier_deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};
