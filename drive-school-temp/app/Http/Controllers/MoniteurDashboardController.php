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
}
