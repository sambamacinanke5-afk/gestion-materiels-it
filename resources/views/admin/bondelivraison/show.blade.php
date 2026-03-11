@extends('partials.admin.master')

@section('content')
<style>
/* --- STYLE GLOBAL --- */
.bon-livraison-container { background:#fff; border-radius:10px; padding:30px; box-shadow:0 0 10px rgba(0,0,0,0.1); font-family:'Nunito', sans-serif; color:#333; }
.bon-header { background:#1f3b57; color:#fff; border-top-left-radius:10px; border-top-right-radius:10px; padding:25px 30px; text-align:center; }
.bon-header h2 { margin:0; font-size:22px; font-weight:bold; letter-spacing:1px; }
.coords {margin-top:20px;}
.coords h5 {color:#1f3b57; font-weight:bold; text-transform:uppercase; font-size:16px;}
.coords ul {list-style:none; padding:0; margin-top:10px; line-height:1.6;}
.coords ul li strong {color:#1f3b57;}
.table thead {background-color:#1f3b57; color:#fff; text-align:center;}
.table th, .table td {vertical-align:middle; text-align:center;}
.invoice-info {background:#f3f3f3; padding:15px; border-radius:8px;}
.invoice-info h3 {color:#1f3b57; font-size:18px;}
.signatures {margin-top:50px; display:flex; justify-content:space-between; text-align:center; flex-wrap:wrap;}
.signatures .bloc {width:45%; margin-bottom:20px;}
.signatures hr {border-top:2px solid #000; width:80%; margin:20px auto 10px;}
.signatures small {color:#555;}
.btn-white {background:#fff; border:1px solid #ccc;}
.btn-white:hover {background:#1f3b57; color:#fff;}
</style>

<div class="content">
    <div class="bon-livraison-container">

        {{-- EN-TÊTE --}}
        <div class="bon-header">
            <h2>Bon de Livraison N° {{ $bondelivraison->bondelivraison }}</h2>
        </div>

        {{-- BOUTONS ACTIONS --}}
        <div class="row mt-3 mb-3">
            <div class="col-sm-12 text-right">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-white">Exporter CSV</button>
                    <button class="btn btn-white" onclick="window.print();"><i class="fa fa-print fa-lg"></i> Imprimer</button>
                </div>
            </div>
        </div>

        {{-- BOUTON RETOUR --}}
        <div class="row mb-3">
            <div class="col-sm-5 col-4">
                <a href="{{ route('bondelivraison.index') }}" class="btn btn-danger btn-rounded">
                    <i class="fa fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        {{-- COORDONNÉES SOCIÉTÉ & FOURNISSEUR --}}
        <div class="row coords">
            <div class="col-md-6">
                <h5>Coordonnées Société</h5>
                <ul>
                    <li><strong>Nom :</strong> Banque Malienne de Solidarité</li>
                    <li><strong>Adresse :</strong> Hamdallaye ACI 2000</li>
                    <li><strong>Téléphone :</strong> 20 70 30 00</li>
                </ul>
            </div>

            @if($bondelivraison->fournisseur)
            <div class="col-md-6">
                <h5>Coordonnées Fournisseur</h5>
                <ul>
                    <li><strong>Nom :</strong> {{ $bondelivraison->fournisseur->nom }}</li>
                    <li><strong>Adresse :</strong> {{ $bondelivraison->fournisseur->Adresse ?? '-' }}</li>
                    <li><strong>Téléphone :</strong> {{ $bondelivraison->fournisseur->Contact ?? '-' }}</li>
                </ul>
            </div>
            @endif
        </div>

        {{-- DÉTAILS DU BON --}}
        <div class="mt-4">
            <h5 class="text-primary font-weight-bold">Détails du bon</h5>
            <ul class="list-unstyled">
                <li><strong>Date de livraison :</strong> {{ \Carbon\Carbon::parse($bondelivraison->date_livraison)->format('d/m/Y') }}</li>
                @if($bondelivraison->date_reception)
                    <li><strong>Date de réception :</strong> {{ \Carbon\Carbon::parse($bondelivraison->date_reception)->format('d/m/Y') }}</li>
                @endif
            </ul>
        </div>

        {{-- TABLEAU DES MATÉRIELS --}}
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Type de matériel</th>
                        <th>Marque</th>
                        <th>Numéro de série</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bondelivraison->lignes as $ligne)
                    <tr>
                        <td>{{ $ligne->materiel->designation ?? 'N/A' }}</td>
                        <td>{{ $ligne->materiel->typemateriel->Designation ?? 'N/A' }}</td>
                        <td>{{ $ligne->materiel->marque->Designation ?? 'N/A' }}</td>
                        <td>{{ $ligne->materiel->numero_serie ?? 'N/A' }}</td>
                    </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Aucun matériel associé à ce bon.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- INFORMATIONS ADDITIONNELLES --}}
        <div class="invoice-info mt-4">
            <h3>Informations additionnelles</h3>
            <p>{{ $bondelivraison->autre_information ?? 'Aucune information supplémentaire.' }}</p>
        </div>

        {{-- SIGNATURES --}}
        <div class="signatures mt-5">
            <div class="bloc">
                <h6>Visa du fournisseur</h6>
                <p>Livré le : {{ $bondelivraison->date_livraison ? \Carbon\Carbon::parse($bondelivraison->date_livraison)->format('d/m/Y') : '—' }}</p>
                <hr>
                <small>Signature du fournisseur</small>
            </div>
            <div class="bloc">
                <h6>Visa du client</h6>
                <p>Reçu le : {{ $bondelivraison->date_reception ? \Carbon\Carbon::parse($bondelivraison->date_reception)->format('d/m/Y') : '—' }}</p>
                <hr>
                <small>Signature du client</small>
            </div>
        </div>

    </div>
</div>
@endsection
