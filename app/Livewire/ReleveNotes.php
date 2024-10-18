<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etudiant;
use Barryvdh\DomPDF\Facade\Pdf;

class ReleveNotes extends Component
{
    public $etudiantId;

    public function mount($id)
    {
        $this->etudiantId = $id;
    }

    public function downloadPdf($id)
    {
        try {
            $etudiant = Etudiant::with(['parcours', 'niveau', 'notesEtudiantUE.uniteEnseignement'])->findOrFail($id);
            $pdf = PDF::loadView('livewire.releve-notes-pdf', compact('etudiant'));
            $filename = 'releve-notes-' . $etudiant->matricule . '.pdf';

            return response()->streamDownload(
                function() use ($pdf) {
                    echo $pdf->output();
                },
                $filename,
                ['Content-Type' => 'application/pdf']
            );
        } catch (\Exception $e) {
            \Log::error('Erreur PDF: ' . $e->getMessage());
            session()->flash('error', 'Erreur lors de la génération du PDF. Veuillez réessayer.');
            return $this->redirect(request()->header('Referer'));
        }
    }

    public function render()
    {
        $etudiant = Etudiant::with(['parcours', 'niveau', 'notesEtudiantUE.uniteEnseignement'])
                            ->findOrFail($this->etudiantId);

        return view('livewire.releve-notes', [
            'etudiant' => $etudiant,
        ]);
    }
}
