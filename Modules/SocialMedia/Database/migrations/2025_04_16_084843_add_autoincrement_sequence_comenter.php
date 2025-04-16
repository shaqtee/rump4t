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
        Schema::table('t_post_detail', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->startingValue(DB::table('t_post_detail')->max('id') + 1)->change();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_post_detail', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->integerIncrements('id')->startingValue(1)->change();
            
        });
    }
};
