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
        Schema::create('amis', function (Blueprint $table) {
            $table->id(); // ID unique pour chaque entrée
            $table->unsignedBigInteger('user_id'); // ID de l'utilisateur
            $table->unsignedBigInteger('friend_id'); // ID de l'ami
            $table->timestamps(); // Colonnes created_at et updated_at

            // Clés étrangères pour garantir l'intégrité référentielle
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('friend_id')->references('id')->on('users')->onDelete('cascade');

            // Empêcher les doublons dans les relations d'amitié
            $table->unique(['user_id', 'friend_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amis');
    }
};
