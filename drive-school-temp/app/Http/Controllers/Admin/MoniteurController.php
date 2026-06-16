<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Moniteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MoniteurController extends Controller
{
    public function index()
    {
        $moniteurs = Moniteur::with('user')->latest()->get();
        return view('admin.moniteurs.index', compact('moniteurs'));
    }

    public function create()
    {
        return view('admin.moniteurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'matricule' => 'required|string|max:255|unique:moniteurs,matricule',
            'telephone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'type_permis' => 'required|string',
        ]);

        $user = User::create([
            'name' => trim($request->nom . ' ' . $request->prenom),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'moniteur',
        ]);

        Moniteur::create([
            'user_id' => $user->id,
            'matricule' => $request->matricule,
            'telephone' => $request->telephone,
            'type_permis' => $request->type_permis,
            'actif' => true,
        ]);

        return redirect()->route('admin.moniteurs.index')->with('success', 'Moniteur ajouté avec succès !');
    }

    public function edit(Moniteur $moniteur)
    {
        return view('admin.moniteurs.edit', compact('moniteur'));
    }

    public function update(Request $request, Moniteur $moniteur)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $moniteur->user_id,
            'matricule' => 'required|string|max:255|unique:moniteurs,matricule,' . $moniteur->id,
            'telephone' => 'required|string|max:255',
            'type_permis' => 'required|string',
            'actif' => 'nullable|boolean',
        ]);

        $moniteur->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $moniteur->update([
            'matricule' => $request->matricule,
            'telephone' => $request->telephone,
            'type_permis' => $request->type_permis,
            'actif' => $request->boolean('actif', true),
        ]);

        return redirect()->route('admin.moniteurs.index')->with('success', 'Moniteur modifié avec succès !');
    }

    public function toggleStatus(Moniteur $moniteur)
    {
        $moniteur->update([
            'actif' => !$moniteur->actif
        ]);

        $status = $moniteur->actif ? 'réactivé' : 'désactivé';
        return redirect()->back()->with('success', "Le moniteur a bien été $status.");
    }
}
