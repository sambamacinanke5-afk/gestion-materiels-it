@extends('partials.user.master')

@section('content')
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            color: #333;
            background: #f4f4f4;
        }

        .bon-livraison-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            margin: 20px auto;
            max-width: 21cm;
            /* Format A4 */
            min-height: 29.7cm;
            box-sizing: border-box;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .bon-header {
            background: #FDCE62;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin-bottom: 20px;
        }

        .bon-header h2 {
            margin: 0 0 5px;
            font-size: 22px;
        }

        .bon-header p {
            margin: 0;
            font-size: 14pt;
        }

        .coords {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .coords h5 {
            margin-bottom: 8px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12pt;
        }

        .coords ul {
            list-style: none;
            padding: 0;
            margin: 0;
            line-height: 1.6;
            font-size: 11pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 11pt;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

        table th {
            background-color: #f0f0f0;
        }

        .totals {
            margin-top: 25px;
            font-weight: bold;
            font-size: 12pt;
        }

        .totals span {
            display: block;
            margin-bottom: 5px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .signatures .bloc {
            width: 45%;
            text-align: center;
            font-size: 11pt;
        }

        .signatures hr {
            margin: 10px auto;
            border-top: 1px solid #000;
            width: 80%;
        }

        @media print {
            body * {
                visibility: hidden !important;
            }

            .content,
            .content * {
                visibility: visible !important;
            }

            .content {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }

            button,
            a {
                display: none !important;
            }
        }
    </style>

    <div class="content">
        <div class="bon-livraison-container" id="printable-bon">

            <!-- EN-TÊTE -->
            <div class="bon-header">
                <h2>Répartition des matériels informatiques</h2>
                <p>Bon de Livraison N° {{ $repartition->bondelivraison->bondelivraison ?? '-' }}</p>
            </div>

            <!-- COORDONNÉES -->
            <div class="coords">
                <div>
                    <h5>Coordonnées Société</h5>
                    <ul>
                        <li><strong>Nom :</strong> Banque Malienne de Solidarité</li>
                        <li><strong>Adresse :</strong> Hamdallaye ACI 2000</li>
                        <li><strong>Téléphone :</strong> 20 70 30 00</li>
                    </ul>
                </div>

                <div>
                    <h5>Coordonnées Fournisseur</h5>
                    <ul>
                        <li><strong>Nom :</strong> {{ $repartition->bondelivraison->fournisseur->nom ?? '-' }}</li>
                        <li><strong>Adresse :</strong> {{ $repartition->bondelivraison->fournisseur->Adresse ?? '-' }}</li>
                        <li><strong>Téléphone :</strong> {{ $repartition->bondelivraison->fournisseur->Contact ?? '-' }}
                        </li>
                    </ul>
                </div>
            </div>

            <!-- TABLEAU PRINCIPAL -->
            <table>
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

            <!-- TOTAUX -->
            <div class="totals">
                <span>Ordinateur complet: {{ $repartition->ordinateur_complets ?? 0 }}</span>
                <span>Ordinateur portable: {{ $repartition->ordinateur_portables ?? 0 }}</span>
                <span>Imprimantes: {{ $repartition->imprimantes ?? 0 }}</span>
                <span>Scanners: {{ $repartition->scanners ?? 0 }}</span>
            </div>

            <!-- SIGNATURES -->
            <div class="signatures">
                <div class="bloc">
                    <p>Livré le :
                        {{ $repartition->bondelivraison->date_livraison
                            ? \Carbon\Carbon::parse($repartition->bondelivraison->date_livraison)->format('d/m/Y')
                            : '—' }}
                    </p>
                    <hr>
                    <small>Signature du responsable d'exploitation</small>
                </div>

                <div class="bloc">
                    <p>Reçu le :
                        {{ $repartition->bondelivraison->date_reception
                            ? \Carbon\Carbon::parse($repartition->bondelivraison->date_reception)->format('d/m/Y')
                            : '—' }}
                    </p>
                    <hr>
                    <small>Signature du destinataire</small>
                </div>
            </div>

        </div>
    </div>
@endsection
