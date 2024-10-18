<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Etudiant;
use App\Models\Parcours;
use App\Models\Niveau;
use App\Models\UniteEnseignement;
use App\Models\NoteEtudiantUE;
use Carbon\Carbon;

class EtudiantsTableSeeder extends Seeder
{
    public function run()
    {
        $filepath = database_path('seeders/etudiants.csv');

        if (!file_exists($filepath)) {
            $this->command->error("Le fichier $filepath n'existe pas.");
            return;
        }

        $file = fopen($filepath, 'r');

        if ($file === false) {
            $this->command->error("Impossible d'ouvrir le fichier $filepath.");
            return;
        }

        // Lire l'en-tête et supprimer le BOM si présent
        $headers = fgetcsv($file, 0, ';');
        $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);

        if ($headers === false) {
            $this->command->error("Le fichier est vide ou mal formaté.");
            fclose($file);
            return;
        }

        $this->command->info("En-têtes du fichier CSV :");
        $this->command->line(implode(', ', $headers));

        $count = 0;
        while (($row = fgetcsv($file, 0, ';')) !== false) {
            $data = array_combine($headers, $row);

            if (!isset($data['matricule'], $data['prenom'], $data['nom'], $data['date_naissance'], $data['parcours'], $data['niveau'])) {
                $this->command->warn("Ligne ignorée car données manquantes : " . implode(', ', $row));
                continue;
            }

            $parcours = Parcours::firstOrCreate(['nom' => $data['parcours']]);
            $niveau = Niveau::firstOrCreate(['nom' => $data['niveau']]);

            $etudiant = Etudiant::create([
                'matricule' => $data['matricule'],
                'prenom' => $data['prenom'],
                'nom' => $data['nom'],
                'date_naissance' => Carbon::createFromFormat('d/m/Y', $data['date_naissance'])->format('Y-m-d'),
                'parcours_id' => $parcours->id,
                'niveau_id' => $niveau->id,
            ]);

            $ues = ['UE1', 'UE2', 'UE3', 'UE5', 'UE6', 'UE7', 'UE8'];
            foreach ($ues as $ue) {
                if (isset($data[$ue])) {
                    $credit = isset($data[$ue . '_credit']) ? intval($data[$ue . '_credit']) : 0;
                    $nom = isset($data[$ue . '_nom']) ? $data[$ue . '_nom'] : "Unité d'enseignement $ue";
                    $uniteEnseignement = UniteEnseignement::firstOrCreate(
                        ['code' => $ue],
                        [
                            'nom' => $nom,
                            'credit' => $credit
                        ]
                    );
                    NoteEtudiantUE::create([
                        'etudiant_id' => $etudiant->id,
                        'unite_enseignement_id' => $uniteEnseignement->id,
                        'note' => str_replace(',', '.', $data[$ue]),
                    ]);
                }
            }

            $etudiant->moyenne = $etudiant->calculerMoyenne();
            $etudiant->resultat = $etudiant->determinerResultat();
            $etudiant->save();

            $count++;
        }

        fclose($file);

        $this->command->info("$count étudiants ont été importés avec succès.");
    }
}
