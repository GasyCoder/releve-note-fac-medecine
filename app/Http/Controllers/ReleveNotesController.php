<?php
namespace App\Http\Controllers;

use App\Models\Etudiant;
use Barryvdh\DomPDF\Facade\Pdf;

class ReleveNotesController extends Controller
{
    public function show($id)
    {
        $etudiant = Etudiant::with(['parcours', 'niveau', 'notesEtudiantUE.uniteEnseignement'])->findOrFail($id);
        return view('releve.releve-notes', compact('etudiant'));
    }

    public function pdf($id)
    {
        $etudiant = Etudiant::with(['parcours', 'niveau', 'notesEtudiantUE.uniteEnseignement'])->findOrFail($id);

        $pdf = PDF::loadView('releve.releve-notes-pdf', compact('etudiant'));

        return $pdf->download('releve-notes-' . $etudiant->matricule . '.pdf');
    }
}
