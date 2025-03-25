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
        Schema::create('conversation_ami', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idEnvoyer');
            $table->unsignedBigInteger('idReceveur');
            $table->text('message')->nullable();
            $table->string('fichier')->nullable();
            $table->timestamps();
            $table->foreign('idEnvoyer')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_ami');
    }
};
