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
    ];

    public function progression()
    {
        return $this->belongsTo(ProgressionDossier::class, 'progression_id');
    }

    public function plageHoraire()
    {
        return $this->belongsTo(plages_horaires::class, 'plage_horaire_id');
    }

    public function evaluation()
    {
        return $this->hasOne(Evaluation::class);
    }
}
