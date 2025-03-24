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
        Schema::create('t_scores_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('event_id'); 
            $table->unsignedBigInteger('hole_id'); 
            $table->float('stroke'); 
            $table->float('putts');
            $table->float('sand_shots'); 
            $table->float('penalties'); 
            $table->string('fairways'); 
            $table->timestamps(); 

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('t_event')->onDelete('cascade');
            $table->foreign('hole_id')->references('id')->on('t_holes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_scores_detail');
    }
};
