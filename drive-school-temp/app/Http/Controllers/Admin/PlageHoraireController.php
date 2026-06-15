<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Moniteur;
use App\Models\PlageHoraire;
use Illuminate\Http\Request;

class PlageHoraireController extends Controller
{
    public function index()
    {
        $moniteurs = Moniteur::with('user')->where('actif', true)->get();

        $plages = PlageHoraire::with('moniteur.user')->get();

        return view('admin.plages.index', compact('moniteurs', 'plages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'moniteur_id' => 'required|exists:moniteurs,id',
            'jour_semaine' => 'required|string',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
        ], [
            'heure_fin.after' => "L'heure de fin doit être supérieure à l'heure de début."
        ]);

        PlageHoraire::create([
            'moniteur_id' => $request->moniteur_id,
            'jour_semaine' => $request->jour_semaine,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
        ]);

        return redirect()->route('admin.plages.index')->with('success', 'Plage horaire configurée avec succès !');
    }

    public function destroy(PlageHoraire $plage)
    {
        $plage->delete();
        return redirect()->route('admin.plages.index')->with('success', 'Créneau supprimé avec succès.');
    }
}
