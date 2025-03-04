<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adminId');
            $table->string('image')->default('default.jpg');
            $table->string('nom');
            $table->boolean('public')->default(true);
            $table->timestamps();

            $table->foreign('adminId')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // supprimer les clé étrangères
        if(Schema::hasTable('clans')) {
            Schema::table('clans', function(Blueprint $table){
                $table->dropForeign(['adminId']);
            });
        }

        Schema::dropIfExists('clans');
    }
};
