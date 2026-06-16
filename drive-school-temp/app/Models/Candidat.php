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
}
