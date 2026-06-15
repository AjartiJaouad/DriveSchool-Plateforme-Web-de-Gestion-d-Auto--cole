<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidat;
use App\Models\Moniteur;
use App\Models\Seance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCandidats = Candidat::count();
        $totalMoniteurs = Moniteur::count();
        $totalSeances = Seance::count();
        
        $seancesValidees = Seance::where('statut', 'valide')->count();
        $successRate = $totalSeances > 0 ? round(($seancesValidees / $totalSeances) * 100) : 0;

        // Chart data: Seances per month for the last 6 months
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Seance::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
            $chartData['labels'][] = $month->translatedFormat('F');
            $chartData['data'][] = $count;
        }

        return view('admin.dashboard', compact(
            'totalCandidats', 
            'totalMoniteurs', 
            'totalSeances', 
            'successRate',
            'chartData'
        ));
    }
}
