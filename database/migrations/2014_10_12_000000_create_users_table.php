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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('otp_code', 10)->nullable();
            $table->date('otp_expired')->nullable();
            $table->string('gender', 2)->nullable();
            $table->date('birth_date')->nullable();
            $table->float('hcp_index')->nullable();
            $table->string('faculty', 100)->nullable();
            $table->string('batch', 50)->nullable();
            $table->string('office_name', 50)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('business_sector', 100)->nullable();
            $table->string('position', 50)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
