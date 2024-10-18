<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevé des notes - {{ $etudiant->matricule }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 25px;
            line-height: 1.3;
        }
        .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 10px;
        }
        .result-container {
            flex: 1;
        }
        .resultat {
            background-color: #f9f9f9;
            color: #000;
            padding: 4px 10px;
            border-radius: 4px;
            display: inline-block;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .titre {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .annee {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 100%;
            height: auto;
        }
        h1 {
            font-size: 18px;
            text-align: center;
            margin: 20px 0;
            text-decoration: underline;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 5px 0;
            font-size: 1.2em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #e6ffe6;
        }
        .header-row {
            background-color: #c2f0c2;
        }
        .total {
            background-color: #c2f0c2;
            font-weight: bold;
        }
        .moyenne {
            background-color: #e6ffe6;
            font-weight: bold;
        }
        .resultat {
            margin-top: 20px;
            font-weight: bold;
        }
        .signature {
            text-align: right;
            margin-top: 40px;
            font-size: 1.2em;
        }
        .text-center {
            text-align: center;
        }
        .size-text{
            font-size: 1.1em;
        }
        .qrcode {
            margin-left: 20px;
        }
        .qrcode img {
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
            padding: 3px;
            background-color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/img/header.png') }}" alt="En-tête">
        <hr>
        <h1 class="titre">RELEVE DES NOTES</h1>
        <span class="annee">Année Universitaire : 2023-2024</span>
    </div>
    <div class="info">
        <p><strong>Nom :</strong> {{ strtoupper($etudiant->nom) }}</p>
        @if($etudiant->prenom != null )
        <p><strong>Prénoms :</strong> {{ $etudiant->prenom }}</p>
        @endif
        <p><strong>Né(e) le :</strong> {{ date('d/m/Y', strtotime($etudiant->date_naissance)) }}</p>
        <p><strong>Numéro matricule :</strong> {{ $etudiant->matricule }}</p>
        <p><strong>Parcours :</strong> {{ strtoupper($etudiant->parcours->nom) }}</p>
        <p><strong>Année d'études :</strong> {{ $etudiant->niveau->nom }}</p>
    </div>

    <table class="size-text">
        <tr class="header-row">
            <th style="width: 2%;">N°</th>
            <th style="width: 60%;">UNITE D'ENSEIGNEMENT</th>
            <th style="width: 5%;">NOTE</th>
            <th style="width: 5%;">CREDIT</th>
        </tr>
        @foreach($etudiant->notesEtudiantUE as $index => $note)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>UE {{ $index + 1 }} : {{ strtoupper($note->uniteEnseignement->nom) }}</td>
            <td class="text-center">{{ number_format($note->note, 2, ',', '') }}/20</td>
            <td class="text-center">{{ number_format($note->uniteEnseignement->credit, 2, ',', '') }}</td>
        </tr>
        @endforeach
        <tr class="total">
            <td colspan="2">TOTAL</td>
            <td>{{ number_format($etudiant->notesEtudiantUE->sum('note'), 2, ',', '') }}/140</td>
            <td>{{ number_format($etudiant->notesEtudiantUE->sum('uniteEnseignement.credit'), 2, ',', '') }}/60</td>
        </tr>
        <tr class="moyenne">
            <td colspan="2">MOYENNE GENERALE</td>
            <td colspan="2">{{ number_format($etudiant->moyenne, 2, ',', '') }}/20</td>
        </tr>
    </table>

    <div class="footer-container">
        <div class="result-container">
            <div class="resultat {{ strtoupper($etudiant->resultat) === 'ADMIS' ? 'admis' : 'non-admis' }}">
                RESULTAT :
                @if(strtoupper($etudiant->resultat) === 'ADMIS')
                    {{ strtoupper($etudiant->resultat) }} EN L2
                @else
                    {{ strtoupper($etudiant->resultat) }}
                @endif
            </div>
            <div class="signature">
                <p>Fait à Mahajanga, le {{ date('d/m/Y') }}</p>
            </div>
        </div>
        <div class="qrcode">
            <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code">
        </div>
    </div>
</body>
</html>
