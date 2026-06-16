<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    use HasFactory;

    protected $fillable = [
        'progression_id',
        'plage_horaire_id',
        'statut',
        'note_performance',
        'competences',
        'remarques',
    ];

    protected $casts = [
        'competences' => 'array',
    ];

    public function progression()
    {
        return $this->belongsTo(ProgressionDossier::class, 'progression_id');
    }

    public function plageHoraire()
    {
        return $this->belongsTo(PlageHoraire::class, 'plage_horaire_id');
    }
}
