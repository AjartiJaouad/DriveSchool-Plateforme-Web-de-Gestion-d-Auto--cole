<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\PlageHoraire;
use App\Models\Seance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $candidat = Candidat::where('user_id', $user->id)->first();

        $seancesAvenir = collect();

        if ($candidat) {
            $seancesAvenir = Seance::whereHas('progression', function ($query) use ($candidat) {
                    $query->where('candidat_id', $candidat->id);
                })
                ->whereHas('plageHoraire', function ($query) {
                    $query->whereDate('date', '>=', now()->toDateString());
                })
                ->with('plageHoraire')
                ->get();
        }

        return view('candidat.index', compact('seancesAvenir'));
    }

    public function events()
    {
        $user = Auth::user();
        $candidat = Candidat::where('user_id', $user->id)->first();

        if (! $candidat) {
            return response()->json([]);
        }

        $events = [];

        $plagesDisponibles = PlageHoraire::where('est_dispo', true)
            ->whereDate('date', '>=', now()->toDateString())
            ->with('moniteur.user')
            ->get();

        foreach ($plagesDisponibles as $plage) {
            $events[] = [
                'id' => 'plage_' . $plage->id,
                'title' => 'Disponible - ' . ($plage->moniteur?->user?->name ?? 'Moniteur'),
                'start' => $plage->date . 'T' . $plage->heure_debut,
                'end' => $plage->date . 'T' . $plage->heure_fin,
                'backgroundColor' => '#3B82F6',
                'extendedProps' => [
                    'statut' => 'disponible',
                ],
            ];
        }

        $seancesAvenir = Seance::whereHas('progression', function ($query) use ($candidat) {
                $query->where('candidat_id', $candidat->id);
            })
            ->whereHas('plageHoraire', function ($query) {
                $query->whereDate('date', '>=', now()->toDateString());
            })
            ->with('plageHoraire')
            ->get();

        foreach ($seancesAvenir as $seance) {
            $events[] = [
                'id' => $seance->id,
                'title' => 'Séance - ' . ucfirst($seance->statut ?? 'en attente'),
                'start' => $seance->plageHoraire->date . 'T' . $seance->plageHoraire->heure_debut,
                'end' => $seance->plageHoraire->date . 'T' . $seance->plageHoraire->heure_fin,
                'backgroundColor' => $seance->statut === 'annule' ? '#EF4444' : '#10B981',
                'extendedProps' => [
                    'statut' => $seance->statut,
                ],
            ];
        }

        return response()->json($events);
    }

    public function reserver(Request $request)
    {
        $request->validate([
            'plage_horaire_id' => 'required|exists:plages_horaires,id',
        ]);

        $candidat = auth()->user()->candidat;

        if (! $candidat) {
            return response()->json(['error' => 'Utilisateur non autorisé.'], 403);
        }

        $progression = $candidat->progressionDossier;

        if (! $progression) {
            return response()->json(['error' => 'Aucun dossier de progression trouvé.'], 400);
        }

        $plage = PlageHoraire::findOrFail($request->plage_horaire_id);

        if (! $plage->est_dispo) {
            return response()->json(['error' => 'Ce créneau est déjà réservé.'], 400);
        }

        Seance::create([
            'progression_id' => $progression->id,
            'plage_horaire_id' => $plage->id,
            'statut' => 'en_attente',
        ]);

        $plage->update(['est_dispo' => false]);

        return response()->json(['success' => 'Séance réservée avec succès !']);
    }

    public function updateStatut(Request $request, Seance $seance)
    {
        $request->validate([
            'action' => 'required|in:annuler',
        ]);

        $user = Auth::user();
        $candidat = Candidat::where('user_id', $user->id)->first();

        if (! $candidat || $seance->progression->candidat_id !== $candidat->id) {
            abort(403);
        }

        if ($request->action === 'annuler') {
            $seance->update(['statut' => 'annule']);
        }

        return redirect()->back()->with('success', 'Séance annulée.');
    }
}
