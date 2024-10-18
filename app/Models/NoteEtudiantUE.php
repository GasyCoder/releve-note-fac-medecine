<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoteEtudiantUE extends Model
{
    protected $fillable = ['etudiant_id', 'unite_enseignement_id', 'note'];

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function uniteEnseignement(): BelongsTo
    {
        return $this->belongsTo(UniteEnseignement::class);
    }
}
