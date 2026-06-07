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
       Schema::create('progression_dossiers', function (Blueprint $table) {
       $table->id();
       $table->foreignId('candidat_id')->constrained('candidats')->onDelete('cascade');
       $table->integer('total_heures_prevues');
       $table->integer('total_heures_realisees')->default(0);
       $table->integer('pourcentage_progres')->default(0);
       $table->string('statut_dossier'); // ex: 'en_cours', 'valide', 'suspendu'
       $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progression_dossiers');
    }
};
