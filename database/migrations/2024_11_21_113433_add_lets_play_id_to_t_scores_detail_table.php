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
        Schema::table('t_scores_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('lets_play_id')->nullable();

            $table->foreign('lets_play_id')->references('id')->on('t_lets_play')->onDelete('set null');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_scores_detail', function (Blueprint $table) {
            //
        });
    }
};
