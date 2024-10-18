<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UniteEnseignement extends Model
{
    protected $fillable = ['code', 'nom', 'credit'];

    public function notesEtudiantUE(): HasMany
    {
        return $this->hasMany(NoteEtudiantUE::class);
    }
}
