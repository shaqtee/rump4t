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
        // change column desc in t_post table from varchar to text
        Schema::table('t_post', function (Blueprint $table) {
            $table->text('desc')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // change column desc in t_post table from text to varchar
        Schema::table('t_post', function (Blueprint $table) {
            $table->string('desc', 255)->nullable()->change();
        });
    }
};
