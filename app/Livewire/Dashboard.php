<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etudiant;

class Dashboard extends Component
{
    public $totalEtudiants;
    public $totalAdmis;
    public $totalRedoublants;
    public $totalExclus;

    public function mount()
    {
        $this->calculateStats();
    }

    public function calculateStats()
    {
        $this->totalEtudiants = Etudiant::count();
        $this->totalAdmis = Etudiant::where('resultat', 'Admis')->count();
        $this->totalRedoublants = Etudiant::where('resultat', 'Redoubler')->count();
        $this->totalExclus = Etudiant::where('resultat', 'Exclus')->count();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
