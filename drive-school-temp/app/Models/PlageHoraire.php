<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlageHoraire extends Model
{
    use HasFactory;

    protected $fillable = [
        'moniteur_id',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
    ];

    public function moniteur()
    {
        return $this->belongsTo(Moniteur::class);
    }
}
