<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etudiant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReleveNotesPdf extends Component
{
    public function downloadPdf($id)
    {
        try {
            $etudiant = Etudiant::with(['parcours', 'niveau', 'notesEtudiantUE.uniteEnseignement'])
                                ->findOrFail($id);
            $qrCode = QrCode::size(300)->generate('Hello, Laravel 11!');
            $pdf = PDF::loadView('livewire.releve-notes-pdf', [
                'etudiant' => $etudiant,
                'qrCode'=> $qrCode
            ]);

            $filename = 'releve-notes-' . $etudiant->matricule . '.pdf';
            Storage::put('public/pdfs/' . $filename, $pdf->output());

            return response()->download(storage_path('app/public/pdfs/' . $filename), $filename, [
                'Content-Type' => 'application/pdf',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Une erreur est survenue lors de la génération du PDF: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.releve-notes-pdf');
    }
}
