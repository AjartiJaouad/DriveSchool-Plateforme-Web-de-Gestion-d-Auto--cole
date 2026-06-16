<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moniteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricule',
        'telephone',
        'type_permis',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }
}
