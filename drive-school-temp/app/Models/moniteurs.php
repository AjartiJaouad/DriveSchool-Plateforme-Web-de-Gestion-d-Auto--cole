<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class moniteurs extends Model
{
    protected $fillable = ['user_id', 'type_permis', 'actif'];

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
}
