@extends('partials.admin.master')

@section('content')
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            color: #333;
        }

        .bon-livraison-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            margin: 0 auto;
            max-width: 21cm;
            /* Format A4 */
            box-sizing: border-box;
        }

        .bon-header {
            background: #FDCE62;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .bon-header h2 {
            margin: 5px 0;
            font-size: 22px;
        }

        .coords {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .coords h5 {
            margin-bottom: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .coords ul {
            list-style: none;
            padding: 0;
            margin: 0;
            line-height: 1.6;
        }

        .logo-container {
            text-align: center;
            margin: 15px 0;
        }

        .logo-container img {
            width: 200px;
            height: auto;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12pt;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            background-color: #FDCE62;
            color: #070707;
            margin: 2px;
            font-size: 11pt;
        }

        .total-materiel {
            margin-top: 20px;
            font-weight: bold;
            font-size: 12pt;
        }

        .total-materiel span {
            display: block;
            margin-bottom: 5px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
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

        /* ------- MODE IMPRESSION ------- */
        @media print {

            /* Masquer tout le reste */
            body * {
                visibility: hidden !important;
            }

            /* Afficher uniquement le bloc imprimable */
            .content,
            .content * {
                visibility: visible !important;
            }

            /* Positionner correctement sur la page imprimée */
            .content {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }

            /* Masquer les boutons ou liens */
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
                    @foreach ($lignes as $ligne)
                        <tr>
                            <td>{{ optional($ligne->service)->Designation ?? '-' }}</td>
                            <td>{{ $ligne->destinataire ?? '-' }}</td>
                            <td>
                                @foreach ($ligne->ligneBLs as $lb)
                                    @php
                                        $mat = $lb->materiel;
                                        $infos = array_filter([
                                            optional($mat->marque)->Designation,
                                            optional($mat->typemateriel)->Designation,
                                            $mat->designation,
                                        ]);
                                    @endphp
                                    <span>{{ implode(' - ', $infos) }}</span><br>
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

            {{-- SIGNATURES --}}
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
