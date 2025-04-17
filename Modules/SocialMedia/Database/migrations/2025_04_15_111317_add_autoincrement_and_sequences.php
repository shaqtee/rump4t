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
        // create sequence t_post_id_seq with read last id;
        DB::statement('CREATE SEQUENCE t_post_id_seq START 1 INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;');
        // set default value for id column in t_post table to use the sequence

        Schema::table('t_post', function (Blueprint $table) {
            // set id column in t_post table to use the sequence
            $table->unsignedBigInteger('id')->default(DB::raw("nextval('t_post_id_seq')"))->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('', function (Blueprint $table) {
            // drop the sequence
            DB::statement('DROP SEQUENCE IF EXISTS t_post_id_seq;');
            // set id column in t_post table to use the default value
            $table->unsignedBigInteger('id')->default(0)->change();
            
        });
    }
};
