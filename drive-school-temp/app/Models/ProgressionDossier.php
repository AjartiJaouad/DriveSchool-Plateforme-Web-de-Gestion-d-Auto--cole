<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressionDossier extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidat_id',
        'total_heures_prevues',
        'total_heures_realisees',
        'pourcentage_progres',
        'statut_dossier',
    ];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }

    public function seances()
    {
        return $this->hasMany(Seance::class, 'progression_id');
    }
}
