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
        Schema::create('score_exercice', function (Blueprint $table) {
            $table->id(); // ID propre à chaque score
            $table->unsignedBigInteger('statistique_id'); 
            $table->integer('semaine'); 
            $table->integer('score'); 
            $table->timestamps();

           
            $table->foreign('statistique_id')->references('id')->on('statistiques')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_exercice');
    }
};
