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
        Schema::create('note_etudiant_u_e_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained()->onDelete('cascade');
            $table->foreignId('unite_enseignement_id')->constrained()->onDelete('cascade');
            $table->decimal('note', 4, 2);
            $table->timestamps();

            $table->unique(['etudiant_id', 'unite_enseignement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_etudiant_u_e_s');
    }
};
