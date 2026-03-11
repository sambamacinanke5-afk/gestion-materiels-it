<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Répartition des matériels</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11pt;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        /* HEADER */
        .header {
            background-color: #FDCE62;
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16pt;
        }

        .header p {
            margin: 0;
            font-size: 12pt;
        }

        /* TABLEAU COORDONNÉES */
        .coords table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .coords td {
            width: 48%;
            vertical-align: top;
            padding: 5px;
            border: 1px solid #000;
        }

        .coords h5 {
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 11pt;
        }

        .coords ul {
            list-style: none;
            padding: 0;
            margin: 0;
            line-height: 1.4;
            font-size: 10pt;
        }

        /* LOGO */
        .logo {
            text-align: center;
            margin: 15px 0;
        }

        .logo img {
            height: 80px;
        }

        /* TABLEAU PRINCIPAL */
        table.main {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 15px;
        }

        table.main th,
        table.main td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
        }

        table.main th {
            background-color: #f0f0f0;
        }

        td.badges {
            text-align: left;
        }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            background-color: #FDCE62;
            color: #000;
            margin: 1px 1px 1px 0;
            font-size: 9pt;
        }

        /* TOTAUX */
        .totals {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 10px;
        }

        .totals span {
            display: block;
            margin-bottom: 2px;
        }

        /* SIGNATURES */
        .signatures table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        .signatures td {
            width: 48%;
            text-align: center;
            vertical-align: top;
            padding: 10px;
            border: none;
        }

        .signatures hr {
            margin: 10px auto;
            border-top: 1px solid #000;
            width: 70%;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <h2>Répartition des matériels informatiques</h2>
            <p>Bon de Livraison N° {{ $repartition->bondelivraison->bondelivraison ?? '-' }}</p>
        </div>

        <!-- COORDONNÉES SOCIÉTÉ / FOURNISSEUR -->
        <div class="coords">
            <table>
                <tr>
                    <td>
                        <h5>Coordonnées Société</h5>
                        <ul>
                            <li><strong>Nom :</strong> Banque Malienne de Solidarité</li>
                            <li><strong>Adresse :</strong> Hamdallaye ACI 2000</li>
                            <li><strong>Téléphone :</strong> 20 70 30 00</li>
                        </ul>
                    </td>
                    <td>
                        @if ($repartition->bondelivraison->fournisseur)
                        <h5>Coordonnées Fournisseur</h5>
                        <ul>
                            <li><strong>Nom :</strong> {{ $repartition->bondelivraison->fournisseur->nom }}</li>
                            <li><strong>Adresse :</strong> {{ $repartition->bondelivraison->fournisseur->Adresse ?? '-' }}</li>
                            <li><strong>Téléphone :</strong> {{ $repartition->bondelivraison->fournisseur->Contact ?? '-' }}</li>
                        </ul>
                        @else
                        &nbsp;
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- LOGO -->
        <div class="logo">
            <img src="{{ public_path('assets/img/bms.jpg') }}" alt="Logo BMS">
        </div>

        <!-- TABLEAU PRINCIPAL -->
        <table class="main">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Destinataire</th>
                    <th>Matériels (BL)</th>
                    <th>Quantité</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($repartition->lignes as $ligne)
                    <tr>
                        <td>{{ optional($ligne->service)->Designation ?? '-' }}</td>
                        <td>{{ $ligne->destinataire ?? '-' }}</td>
                        <td class="badges">
                            @php
                                $lbIds = json_decode($ligne->lignebondelivraison_id, true) ?? [];
                                $ligneBLs = \App\Models\LigneBondelivraison::with('materiel', 'materiel.marque', 'materiel.typemateriel')
                                    ->whereIn('id', $lbIds)
                                    ->get();
                            @endphp
                            @foreach ($ligneBLs as $lb)
                                @php
                                    $mat = $lb->materiel;
                                    $infos = array_filter([
                                        optional($mat->marque)->Designation,
                                        optional($mat->typemateriel)->Designation,
                                        $mat->designation,
                                    ]);
                                @endphp
                                <span class="badge">{{ implode(' - ', $infos) }}</span>
                            @endforeach
                        </td>
                        <td>{{ $ligne->quantite ?? 1 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTAUX GLOBAUX -->
        <div class="totals">
            <span>Ordinateur complet: {{ $repartition->ordinateur_complets ?? 0 }}</span>
            <span>Ordinateur portable: {{ $repartition->ordinateur_portables ?? 0 }}</span>
            <span>Imprimantes: {{ $repartition->imprimantes ?? 0 }}</span>
            <span>Scanners: {{ $repartition->scanners ?? 0 }}</span>
        </div>

        <!-- SIGNATURES -->
        <div class="signatures">
            <table>
                <tr>
                    <td>
                        <p>Livré le: {{ optional($repartition->bondelivraison->date_livraison) ? \Carbon\Carbon::parse($repartition->bondelivraison->date_livraison)->format('d/m/Y') : '-' }}</p>
                        <hr>
                        <small>Signature du responsable d'exploitation</small>
                    </td>
                    <td>
                        <p>Reçu le: {{ optional($repartition->bondelivraison->date_reception) ? \Carbon\Carbon::parse($repartition->bondelivraison->date_reception)->format('d/m/Y') : '-' }}</p>
                        <hr>
                        <small>Signature du Chef du service Achats</small>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>

</html>
