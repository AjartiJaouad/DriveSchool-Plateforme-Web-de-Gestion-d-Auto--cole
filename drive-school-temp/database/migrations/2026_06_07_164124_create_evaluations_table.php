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
      Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seance_id')->constrained('seances')->onDelete('cascade');
            $table->integer('note_performance');
            $table->json('competences')->nullable(); 
            $table->text('remarques')->nullable();
            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
