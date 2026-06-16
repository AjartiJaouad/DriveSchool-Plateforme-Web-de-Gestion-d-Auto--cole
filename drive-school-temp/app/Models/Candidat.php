<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    protected $fillable = ['user_id', 'type_permis', 'date_inscription', 'statut'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function progressionDossier()
    {
        return $this->hasOne(ProgressionDossier::class);
    }

    public function seances()
    {
        return $this->hasManyThrough(
            Seance::class,
            ProgressionDossier::class,
            'candidat_id', // Foreign key on progression_dossiers table
            'progression_id', // Foreign key on seances table
            'id', // Local key on candidats table
            'id' // Local key on progression_dossiers table
        );
    }
}
