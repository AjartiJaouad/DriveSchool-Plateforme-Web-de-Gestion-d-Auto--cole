<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\PlageHoraire;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $plages = PlageHoraire::all();

        $reservations = auth()->user()->candidat->seances ?? collect();

        return view('candidat.index', compact('plages', 'reservations'));
    }
}
