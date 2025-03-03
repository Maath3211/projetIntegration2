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
        Schema::create('utilisateur_clan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idEnvoyer');
            $table->unsignedBigInteger('idClan');
            $table->text('message')->nullable();
            $table->string('fichier')->nullable();
            $table->timestamps();
            $table->foreign('idEnvoyer')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idClan')->references('id')->on('clans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateur_clan');
    }
};
