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
        Schema::create('utilisateur_ami', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('envoyeur_id')->unsigned();
            $table->bigInteger('receveur_id')->unsigned();
            $table->foreign('envoyeur_id','envoyeur')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receveur_id','receveur')->references('id')->on('users')->onDelete('cascade');
            $table->text('message');
            $table->timestamps();
            $table->dateTime('read_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('utilisateur_ami');
    }
};
