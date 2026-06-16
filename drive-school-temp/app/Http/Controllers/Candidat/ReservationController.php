<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
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

        $seancesAvenir = Seance::whereHas('progression', function ($query) use ($candidat) {
                $query->where('candidat_id', $candidat->id);
            })
            ->whereHas('plageHoraire', function ($query) {
                $query->whereDate('date', '>=', now()->toDateString());
            })
            ->with('plageHoraire')
            ->get();

        $events = $seancesAvenir->map(function ($seance) {
            return [
                'id' => $seance->id,
                'title' => 'Séance - ' . ucfirst($seance->statut ?? 'en attente'),
                'start' => $seance->plageHoraire->date . 'T' . $seance->plageHoraire->heure_debut,
                'end' => $seance->plageHoraire->date . 'T' . $seance->plageHoraire->heure_fin,
                'backgroundColor' => $seance->statut === 'annule' ? '#EF4444' : '#10B981',
                'extendedProps' => [
                    'statut' => $seance->statut,
                ],
            ];
        });

        return response()->json($events);
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
