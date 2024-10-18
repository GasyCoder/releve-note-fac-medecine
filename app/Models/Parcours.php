<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parcours extends Model
{
    protected $fillable = ['nom'];

    public function etudiants(): HasMany
    {
        return $this->hasMany(Etudiant::class);
    }
}
