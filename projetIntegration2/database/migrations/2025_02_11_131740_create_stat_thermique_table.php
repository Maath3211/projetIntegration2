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
        Schema::create('statThermique', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('type_activite'); // 1: Bras, 2: Jambe, 3: Pectoraux, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statThermique');
    }
};
