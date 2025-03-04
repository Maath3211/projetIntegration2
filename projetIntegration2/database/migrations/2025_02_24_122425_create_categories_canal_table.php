<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories_canal', function (Blueprint $table) {
            $table->id();
            $table->string('categorie', 50);
            $table->unsignedBigInteger('clanId');

            $table->foreign('clanId')->references('id')->on('clans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // supprimer les clés étrangères
        if(Schema::hasTable('categories_canal')) {
            Schema::table('categories_canal', function(Blueprint $table){
                $table->dropForeign(['clanId']);
            });
        }

        Schema::dropIfExists('categories_canal');
    }
};
