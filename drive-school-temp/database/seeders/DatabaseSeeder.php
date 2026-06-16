<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Candidat;
use App\Models\Moniteur;
use App\Models\ProgressionDossier;
use App\Models\PlageHoraire;
use App\Models\Seance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        $admin = User::firstOrCreate(['email' => 'admin@driveschool.com'], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Moniteur user
        $moniteurUser = User::firstOrCreate(['email' => 'moniteur@driveschool.com'], [
            'name' => 'Jean Dupont',
            'password' => Hash::make('password'),
            'role' => 'moniteur',
        ]);

        $moniteur = Moniteur::firstOrCreate(['user_id' => $moniteurUser->id], [
            'specialites' => json_encode(['B', 'Moto']),
            'date_embauche' => '2025-01-01',
            'type_permis' => 'B',
            'actif' => true,
        ]);

        // Candidat user
        $candidatUser = User::firstOrCreate(['email' => 'candidat@driveschool.com'], [
            'name' => 'Alice Martin',
            'password' => Hash::make('password'),
            'role' => 'candidat',
        ]);

        $candidat = Candidat::firstOrCreate(['user_id' => $candidatUser->id], [
            'type_permis' => 'B',
            'date_inscription' => Carbon::now()->subMonths(2),
            'statut' => 'actif',
        ]);

        // Progression Dossier
        $progression = ProgressionDossier::firstOrCreate(['candidat_id' => $candidat->id], [
            'total_heures_prevues' => 20,
            'total_heures_realisees' => 2,
            'pourcentage_progres' => 10,
            'statut_dossier' => 'en_cours',
        ]);

        // Plage Horaire
        $plage = PlageHoraire::firstOrCreate([
            'moniteur_id' => $moniteur->id,
            'date' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'heure_debut' => '10:00:00',
            'heure_fin' => '11:00:00',
        ], [
            'statut' => 'reserve'
        ]);

        // Seance
        Seance::firstOrCreate([
            'progression_id' => $progression->id,
            'plage_horaire_id' => $plage->id,
        ], [
            'statut' => 'en_attente'
        ]);
    }
}
