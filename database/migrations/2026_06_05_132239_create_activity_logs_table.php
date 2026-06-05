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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('users_id')->nullable();
            $table->string('users_username', 100)->nullable();
            $table->string('users_role', 50)->nullable();
            $table->string('action', 50);
            $table->string('subject_type', 150)->nullable();
            $table->unsignedInteger('subject_id')->nullable();
            $table->text('description');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Set null on delete so we keep log history even if user is deleted
            $table->foreign('users_id')
                  ->references('users_id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

