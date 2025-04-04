<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clan_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clan_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('clan_id')->references('id')->on('clans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // supprimer les clés étrangères
        if(Schema::hasTable('clan_users')) {
            Schema::table('clan_users', function(Blueprint $table){
                $table->dropForeign(['clan_id']);
                $table->dropForeign(['user_id']);
            });
        }

        Schema::dropIfExists('clan_users');
    }
};
