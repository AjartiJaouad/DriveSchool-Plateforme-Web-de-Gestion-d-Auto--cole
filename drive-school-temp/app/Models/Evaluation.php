<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'seance_id',
        'note_performance',
        'competences',
        'remarques',
    ];

    protected $casts = [
        'competences' => 'array',
    ];

    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }
}
