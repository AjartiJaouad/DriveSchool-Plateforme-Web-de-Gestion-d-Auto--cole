<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\PlageHoraire;

class MoniteurDashboardController extends Controller
{
    public function index()
    {
        return view('moniteur.dashboard');
    }

    public function events(Request $request)
    {
        // 1. Récupérer le moniteur connecté
        $moniteur = auth()->user()->moniteur;

        if (!$moniteur) {
            return response()->json([]);
        }

        $events = [];

        // 2. Récupérer les plages horaires disponibles ajoutées par l'Admin
        $plagesLibres = PlageHoraire::where('moniteur_id', $moniteur->id)
            ->where('est_dispo', true)
            ->get();

        foreach ($plagesLibres as $plage) {
            $events[] = [
                'id' => 'plage_' . $plage->id,
                'title' => '🕒 Disponible (Admin)',
                'start' => $plage->date . 'T' . $plage->heure_debut,
                'end' => $plage->date . 'T' . $plage->heure_fin,
                'backgroundColor' => '#3B82F6', // Couleur bleue pour les créneaux libres
                'extendedProps' => [
                    'statut' => 'disponible',
                    'candidat' => 'Aucun',
                ]
            ];
        }

        // 3. Récupérer les séances réservées pour ce moniteur spécifiquement
        $seances = Seance::with(['progression.candidat', 'plageHoraire'])
            ->where('moniteur_id', $moniteur->id)
            ->get();

        foreach ($seances as $seance) {
            if ($seance->plageHoraire) {
                $candidatName = $seance->progression->candidat->user->name ?? 'Candidat inconnu';
                $events[] = [
                    'id' => $seance->id,
                    'title' => '🚗 Séance: ' . $candidatName,
                    'start' => $seance->plageHoraire->date . 'T' . $seance->plageHoraire->heure_debut,
                    'end' => $seance->plageHoraire->date . 'T' . $seance->plageHoraire->heure_fin,
                    'backgroundColor' => $seance->statut === 'valide' ? '#10B981' : ($seance->statut === 'annule' ? '#EF4444' : '#F59E0B'),
                    'extendedProps' => [
                        'statut' => $seance->statut,
                        'candidat' => $candidatName,
                    ]
                ];
            }
        }

        return response()->json($events);
    }

    public function updateStatut(Request $request, Seance $seance)
    {
        $request->validate([
            'statut' => 'required|in:valide,annule,en_attente'
        ]);

        $seance->update(['statut' => $request->statut]);

        if ($request->statut === 'valide' && $seance->progression) {
            $seance->progression->increment('total_heures_realisees');
            if ($seance->progression->total_heures_prevues > 0) {
                $seance->progression->pourcentage_progres = min(100, round(($seance->progression->total_heures_realisees / $seance->progression->total_heures_prevues) * 100));
                $seance->progression->save();
            }
        } elseif ($request->statut === 'annule') {
             if ($seance->getOriginal('statut') === 'valide' && $seance->progression) {
                 $seance->progression->decrement('total_heures_realisees');
                 if ($seance->progression->total_heures_prevues > 0) {
                    $seance->progression->pourcentage_progres = min(100, max(0, round(($seance->progression->total_heures_realisees / $seance->progression->total_heures_prevues) * 100)));
                    $seance->progression->save();
                 }
             }
        }

        return redirect()->back()->with('success', 'Statut de la séance mis à jour.');
    }

    public function storeEvaluation(Request $request, Seance $seance)
    {
        $request->validate([
            'note_performance' => 'required|integer|min:1|max:5',
            'remarques' => 'nullable|string',
            'competences' => 'nullable|array'
        ]);

        $seance->update([
            'note_performance' => $request->note_performance,
            'remarques' => $request->remarques,
            'competences' => $request->competences ?? [],
        ]);

        return redirect()->back()->with('success', 'Évaluation enregistrée avec succès.');
    }
}
