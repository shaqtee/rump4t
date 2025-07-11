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
        Schema::table('t_regions', function (Blueprint $table) {
            $table->softDeletes(); // Add soft deletes column
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_regions', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Remove soft deletes column
            
        });
    }
};
