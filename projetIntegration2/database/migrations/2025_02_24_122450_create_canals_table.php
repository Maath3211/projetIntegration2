<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('canals', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 50);
            $table->unsignedBigInteger('clanId');
            $table->unsignedBigInteger('categorieId');

            $table->foreign('categorieId')->references('id')->on('categories_canal')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // supprimer les clés étrangères
        if(Schema::hasTable('canals')) {
            Schema::table('canals', function(Blueprint $table){
                $table->dropForeign(['categorieId']);
            });
        }

        Schema::dropIfExists('canals');
    }
};
