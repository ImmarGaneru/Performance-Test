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
        // Create the main user table with the custom schema
        Schema::create('tb_user', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('nama', 80)->nullable();
            $table->string('kode', 50);
            $table->string('password');
            $table->tinyInteger('plant_id')->unsigned()->nullable();
            $table->string('unit_id', 50)->nullable();
            $table->smallInteger('status')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();

            // Define foreign key constraint
            // Note: Ensure 'tb_plant' migration runs before this one.
            // The filename suggests it does.
            $table->foreign('plant_id')->references('plant_id')->on('tb_plant')->onDelete('set null');
        });

        // Create the supporting password reset tokens table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Create the supporting sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id', 10)->nullable()->index();
            $table->foreign('user_id')->references('id')->on('tb_user')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('tb_user');
    }
};
