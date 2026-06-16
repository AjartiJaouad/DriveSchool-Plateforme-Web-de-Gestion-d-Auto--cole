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
      Schema::create('seances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('progression_id')->constrained('progression_dossiers')->onDelete('cascade');
            $table->foreignId('plage_horaire_id')->constrained('plages_horaires')->onDelete('cascade');
            $table->enum('statut', ['en_attente', 'valide', 'annule'])->default('en_attente');
            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seances');
    }
};
