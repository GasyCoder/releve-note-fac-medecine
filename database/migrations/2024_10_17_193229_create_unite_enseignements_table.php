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
        Schema::create('unite_enseignements', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('nom'); // Ajoutez cette ligne
            $table->integer('credit')->default(0); // Ajoutez une valeur par défaut si nécessaire
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unite_enseignements');
    }
};
