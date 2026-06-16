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
        Schema::table('moniteurs', function (Blueprint $table) {
            if (!Schema::hasColumn('moniteurs', 'type_permis')) {
                $table->string('type_permis')->nullable();
            }
            if (Schema::hasColumn('moniteurs', 'est_actif') && !Schema::hasColumn('moniteurs', 'actif')) {
                $table->renameColumn('est_actif', 'actif');
            }
            if (!Schema::hasColumn('moniteurs', 'actif')) {
                $table->boolean('actif')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('moniteurs', function (Blueprint $table) {
            if (Schema::hasColumn('moniteurs', 'type_permis')) {
                $table->dropColumn('type_permis');
            }
            if (Schema::hasColumn('moniteurs', 'actif')) {
                $table->dropColumn('actif');
            }
        });
    }
};
