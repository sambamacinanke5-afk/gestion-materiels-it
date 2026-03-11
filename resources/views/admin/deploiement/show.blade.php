@extends('partials.admin.master')

@section('content')
<style>
    body {
        background: #f4f4f4;
        font-family: Arial, sans-serif;
        color: #000;
    }

    .fiche-container {
        background: #fff;
        padding: 30px;
        max-width: 900px;
        margin: 20px auto;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .logo-container {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo-container img {
        height: 80px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .header .right {
        text-align: right;
        font-size: 13px;
    }

    h2 {
        text-align: center;
        margin: 20px 0;
        text-transform: uppercase;
        font-size: 22px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 14px;
    }

    table th, table td {
        border: 1px solid #000;
        padding: 8px;
        vertical-align: middle;
    }

    table th {
        background: #f5f5f5;
        font-weight: bold;
    }

    .footer {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        font-size: 14px;
    }

    .no-print {
        text-align: center;
        margin-top: 20px;
    }

    @media print {
        body { background: #fff; }
        .no-print { display: none; }
    }
</style>

<div class="fiche-container">

    {{-- LOGO --}}
    <div class="logo-container">
        <img src="{{ asset('assets/img/bms.jpg') }}" alt="Logo BMS">
    </div>

    {{-- HEADER --}}
    <div class="header">
        <div>
            <strong>BMS S.A</strong><br>
            Banque Malienne de Solidarité
        </div>
        <div class="right">
            Réf : Fiche de déploiement<br>
            Version : 2.0<br>
            Direction des Systèmes d’Informations
        </div>
    </div>

    <h2>Fiche de déploiement</h2>

    {{-- INFORMATIONS DE BASE --}}
    <table>
        <tr>
            <th>Agence / Direction</th>
            <td>{{ $deploiement->Direction }}</td>
        </tr>
        <tr>
            <th>Utilisateur</th>
            <td>{{ $deploiement->Utilisateur }}</td>
        </tr>
        <tr>
            <th>Poste</th>
            <td>{{ $deploiement->Poste }}</td>
        </tr>
    </table>

    {{-- MATÉRIELS --}}
    <table>
        <tr>
            <th>Matériel</th>
            <th>Marque</th>
            <th>Type</th>
            <th>Numéro de Série</th>
        </tr>
        @foreach ($deploiement->ligneDeploiements as $ligne)
            @php $mat = optional($ligne->lignebondelivraison)->materiel; @endphp
            <tr>
                <td>{{ $mat->designation ?? '-' }}</td>
                <td>{{ optional($mat->marque)->Designation ?? '-' }}</td>
                <td>{{ optional($mat->typemateriel)->Designation ?? '-' }}</td>
                <td>{{ optional($ligne->lignebondelivraison)->materiel->numero_serie ?? '-' }}</td>
            </tr>
        @endforeach
    </table>

    {{-- CONFIGURATION TECHNIQUE --}}
    <table>
        <tr>
            <th>Système</th>
            <th>RAM (Go)</th>
            <th>Disque (Go)</th>
            <th>Nom ordinateur</th>
        </tr>
        <tr>
            <td>{{ $deploiement->Systeme ?? '-' }}</td>
            <td>{{ $deploiement->Ram ?? '-' }}</td>
            <td>{{ $deploiement->Disque ?? '-' }}</td>
            <td>{{ $deploiement->Nomordinateur ?? '-' }}</td>
        </tr>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <div>Bamako, le {{ \Carbon\Carbon::parse($deploiement->Datecreation)->format('d/m/Y') }}</div>
        <div>
            Agent DSI<br><br>
            ______________________
        </div>
    </div>

    {{-- BOUTONS --}}
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">Imprimer</button>
        <a href="{{ route('deploiement.index') }}" class="btn btn-secondary">Retour</a>
    </div>

</div>
@endsection
