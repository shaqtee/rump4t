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
           // create parent ID just int
              $table->unsignedBigInteger('parent_id')->nullable()->after('id_post');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('', function (Blueprint $table) {
            // drop parent ID
            $table->dropColumn('parent_id');            
        });
    }
};
