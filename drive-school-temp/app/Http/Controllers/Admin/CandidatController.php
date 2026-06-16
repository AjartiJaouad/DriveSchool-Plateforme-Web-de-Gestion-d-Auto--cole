<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use Illuminate\Http\Request;

class CandidatController extends Controller
{
    public function index()
    {
        $candidats = Candidat::with('user')->latest()->paginate(15);

        return view('admin.candidats.index', compact('candidats'));
    }

    public function show(Candidat $candidat)
    {
        $candidat->load('user', 'progressionDossier');

        return view('admin.candidats.show', compact('candidat'));
    }
}
