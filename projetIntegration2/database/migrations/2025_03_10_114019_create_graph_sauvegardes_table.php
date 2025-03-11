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
        Schema::create('graph_sauvegardes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['global', 'clan']);
            $table->foreignId('clan_id')->nullable()->constrained('clans')->onDelete('cascade');
            $table->string('titre');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->json('data');
            $table->date('date_expiration'); // Will be set to 90 days from creation
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graph_sauvegardes');
    }
};
