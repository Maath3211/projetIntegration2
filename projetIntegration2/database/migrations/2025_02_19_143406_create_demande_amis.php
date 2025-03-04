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
        Schema::create('demande_amis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requester_id'); // utilisateur qui envoie la demande
            $table->unsignedBigInteger('requested_id');  // utilisateur qui reçoit la demande
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->timestamps();

            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('requested_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['requester_id', 'requested_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_amis');
    }
};
