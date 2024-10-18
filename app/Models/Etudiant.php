<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etudiant extends Model
{
    protected $fillable = [
        'matricule', 'prenom', 'nom', 'date_naissance',
        'parcours_id', 'niveau_id', 'moyenne', 'resultat', 'pdf_path',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function parcours(): BelongsTo
    {
        return $this->belongsTo(Parcours::class);
    }

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class);
    }

    public function notesEtudiantUE(): HasMany
    {
        return $this->hasMany(NoteEtudiantUE::class);
    }

    public function calculerMoyenne(): float
    {
        return $this->notesEtudiantUE()->avg('note') ?? 0;
    }

    public function determinerResultat(): string
    {
        $moyenne = $this->calculerMoyenne();
        if ($moyenne >= 10) {
            return 'Admis';
        } elseif ($moyenne >= 8) {
            return 'Redoubler';
        } else {
            return 'Exclus';
        }
    }
}
