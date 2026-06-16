<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;

class MoniteurDashboardController extends Controller
{
    public function index()
    {
        return view('moniteur.dashboard');
    }

    public function events(Request $request)
    {
        // $request->start, $request->end contain the date range from FullCalendar
        $seances = Seance::with(['progression.candidat', 'plageHoraire'])
            ->get();

        $events = $seances->map(function ($seance) {
            $candidatName = $seance->progression->candidat->user->name ?? 'Candidat inconnu';
            return [
                'id' => $seance->id,
                'title' => 'Séance: ' . $candidatName,
                // Fullcalendar needs start and end times. PlageHoraire has 'heure_debut', 'heure_fin', 'date'.
                // If plageHoraire isn't setup correctly, this might break, but we'll adapt.
                'start' => $seance->plageHoraire->date . 'T' . $seance->plageHoraire->heure_debut,
                'end' => $seance->plageHoraire->date . 'T' . $seance->plageHoraire->heure_fin,
                'backgroundColor' => $seance->statut === 'valide' ? '#10B981' : ($seance->statut === 'annule' ? '#EF4444' : '#F59E0B'),
                'extendedProps' => [
                    'statut' => $seance->statut,
                    'candidat' => $candidatName,
                ]
            ];
        });

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
        } elseif ($request->statut === 'annule' && clone $seance->wasChanged('statut')) {
             // Handle cancellation if it was previously valide, we might want to decrement
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
