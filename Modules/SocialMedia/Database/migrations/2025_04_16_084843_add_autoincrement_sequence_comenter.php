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
            $table->unsignedBigInteger('id')->change();

            DB::statement("DO $$ BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_class WHERE relname = 't_post_detail_id_seq') THEN
                    CREATE SEQUENCE t_post_detail_id_seq;
                END IF;
            END $$;");
            DB::statement("ALTER SEQUENCE t_post_detail_id_seq RESTART WITH " . (DB::table('t_post_detail')->max('id') + 1));
            // set default value to the next value of the sequence
            $table->bigInteger('id')->default(DB::raw("nextval('t_post_detail_id_seq')"))->change();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_post_detail', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->unsignedBigInteger('id')->startingValue(1)->change();
            
        });
    }
};
