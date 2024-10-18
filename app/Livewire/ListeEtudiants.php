<?php

namespace App\Livewire;

use App\Models\Niveau;
use Livewire\Component;
use App\Models\Etudiant;
use App\Models\Parcours;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ListeEtudiants extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $parcours = '';

    #[Url]
    public $niveau = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingParcours()
    {
        $this->resetPage();
    }

    public function updatingNiveau()
    {
        $this->resetPage();
    }

    public function generatePdf($id)
    {
        $etudiant = Etudiant::with(['parcours', 'niveau', 'notesEtudiantUE.uniteEnseignement'])->findOrFail($id);

        $qrCodeContent = "Nom: " . $etudiant->nom . "\n" .
        "Prénom: " . $etudiant->prenom . "\n" .
        "Matricule: " . $etudiant->matricule . "\n" .
        "Parcours: " . $etudiant->parcours->nom . "\n" .
        "Niveau: " . $etudiant->niveau->nom . "\n" .
        "Moyenne: " . number_format($etudiant->moyenne, 2, ',', '') . "/20\n" .
        "Résultat: " . $etudiant->resultat;

        $qrCode = QrCode::format('svg')->size(100)->generate($qrCodeContent);

        $pdf = PDF::loadView('livewire.releve-notes-pdf', [
            'etudiant' => $etudiant,
            'qrCode'=> $qrCode
        ]);

        $filename = 'releve-notes-' . $etudiant->matricule . '.pdf';
        $path = 'pdfs/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        return Storage::disk('public')->url($path);
    }

    public function render()
    {
        $etudiants = Etudiant::with(['parcours', 'niveau'])
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('matricule', 'like', "%{$search}%");
                });
            })
            ->when($this->parcours, function ($query, $parcours) {
                $query->whereHas('parcours', function ($q) use ($parcours) {
                    $q->where('nom', $parcours);
                });
            })
            ->when($this->niveau, function ($query, $niveau) {
                $query->whereHas('niveau', function ($q) use ($niveau) {
                    $q->where('nom', $niveau);
                });
            })
            ->paginate(10);

        return view('livewire.liste-etudiants', [
            'etudiants' => $etudiants,
            'parcoursList' => Parcours::pluck('nom'),
            'niveauxList' => Niveau::pluck('nom'),
        ]);
    }
}
