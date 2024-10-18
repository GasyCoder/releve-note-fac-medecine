<?php

namespace App\Imports;

use App\Models\Etudiant;
use App\Models\Parcours;
use App\Models\Niveau;
use App\Models\UniteEnseignement;
use App\Models\NoteEtudiantUE;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EtudiantsImport
{
    public function import($filePath)
    {
        $data = $this->readCsv($filePath);

        DB::transaction(function () use ($data) {
            foreach ($data as $row) {
                $this->processRow($row);
            }
        });
    }

    private function readCsv($filePath)
    {
        $file = fopen($filePath, 'r');
        $headers = fgetcsv($file);
        $data = new Collection();

        while (($row = fgetcsv($file)) !== false) {
            $data->push(array_combine($headers, $row));
        }

        fclose($file);
        return $data;
    }

    private function processRow($row)
    {
        $parcours = Parcours::firstOrCreate(['nom' => $row['parcours']]);
        $niveau = Niveau::firstOrCreate(['nom' => $row['niveau']]);

        $etudiant = Etudiant::create([
            'matricule' => $row['matricule'],
            'prenom' => $row['prenom'],
            'nom' => $row['nom'],
            'date_naissance' => $this->transformDate($row['date_naissance']),
            'parcours_id' => $parcours->id,
            'niveau_id' => $niveau->id,
        ]);

        $ues = ['UE1', 'UE2', 'UE3', 'UE5', 'UE6', 'UE7', 'UE8'];
        foreach ($ues as $ue) {
            if (isset($row[$ue])) {
                $uniteEnseignement = UniteEnseignement::firstOrCreate(['code' => $ue]);
                NoteEtudiantUE::create([
                    'etudiant_id' => $etudiant->id,
                    'unite_enseignement_id' => $uniteEnseignement->id,
                    'note' => $row[$ue],
                ]);
            }
        }

        $etudiant->moyenne = $etudiant->calculerMoyenne();
        $etudiant->resultat = $etudiant->determinerResultat();
        $etudiant->save();

        return $etudiant;
    }

    private function transformDate($value)
    {
        return Carbon::parse($value);
    }
}
