<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlageHoraire extends Model
{
    use HasFactory;

    protected $table = 'plages_horaires';

    protected $fillable = [
        'moniteur_id',
        'date',
        'heure_debut',
        'heure_fin',
        'est_dispo',
    ];

    public function moniteur()
    {
        return $this->belongsTo(Moniteur::class);
    }
}
