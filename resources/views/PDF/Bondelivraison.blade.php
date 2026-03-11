<style>
    /* ================= FORMAT A4 ================= */
    @page {
        size: A4 portrait;
        margin: 15mm;
    }

    /* ================= GLOBAL ================= */
    body {
        font-family: 'Nunito', sans-serif;
        color: #333;
        font-size: 13px;
        line-height: 1.3;
        margin: 0;
        padding: 0;
    }

    /* ================= CONTENEUR ================= */
    .bon-livraison-container {
        background: #fff;
        padding: 10mm;
    }

    /* ================= HEADER ================= */
    .bon-header {
        background: #1f3b57;
        color: #fff;
        padding: 12px;
        text-align: center;
    }

    .bon-header h2 {
        margin: 0;
        font-size: 20px;
        letter-spacing: 1px;
    }

    /* ================= COORDONNÉES ================= */
    .coords-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 12px;
    }

    .coords-table td {
        width: 50%;
        vertical-align: top;
        border: 1px solid #ddd;
        padding: 8px;
    }

    .coords-table h5 {
        font-size: 14px;
        margin-bottom: 5px;
        color: #1f3b57;
        text-transform: uppercase;
    }

    .coords-table ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .coords-table ul li {
        margin-bottom: 3px;
    }

    /* ================= DÉTAILS ================= */
    .details-bon {
        margin-top: 8px;
    }

    .details-bon h5 {
        font-size: 14px;
        margin-bottom: 5px;
        color: #1f3b57;
    }

    /* ================= TABLEAU ================= */
    .table-responsive {
        margin-top: 8px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .table th,
    .table td {
        border: 1px solid #1f3b57;
        padding: 5px;
        text-align: center;
    }

    .table thead {
        background: #1f3b57;
        color: #fff;
    }

    /* ================= INFOS ================= */
    .invoice-info {
        margin-top: 8px;
        padding: 8px;
        background: #f3f3f3;
        border-radius: 4px;
    }

    .invoice-info h3 {
        font-size: 14px;
        margin-bottom: 4px;
        color: #1f3b57;
    }

    /* ================= SIGNATURES ================= */
    .signatures-table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .signatures-table td {
        width: 50%;
        text-align: center;
        vertical-align: top;
    }

    .signatures-table hr {
        border-top: 1px solid #000;
        width: 80%;
        margin: 10px auto 5px;
    }

    .signatures-table small {
        color: #555;
    }

    /* ================= IMPRESSION ================= */
    @media print {
        .btn-group,
        .btn-white {
            display: none !important;
        }
    }
    </style>



<div class="bon-livraison-container">

    <!-- EN-TÊTE -->
    <div class="bon-header">
        <h2>Bon de Livraison N° {{ $bondelivraison->bondelivraison }}</h2>
    </div>

    <!-- COORDONNÉES -->
    <table class="coords-table">
        <tr>
            <!-- SOCIÉTÉ -->
            <td>
                <h5>Coordonnées Société</h5>
                <ul>
                    <li><strong>Nom :</strong> Banque Malienne de Solidarité</li>
                    <li><strong>Adresse :</strong> Hamdallaye ACI 2000</li>
                    <li><strong>Téléphone :</strong> 20 70 30 00</li>
                </ul>
            </td>

            <!-- FOURNISSEUR -->
            <td>
                @if ($bondelivraison->fournisseur)
                    <h5>Coordonnées Fournisseur</h5>
                    <ul>
                        <li><strong>Nom :</strong> {{ $bondelivraison->fournisseur->nom }}</li>
                        <li><strong>Adresse :</strong> {{ $bondelivraison->fournisseur->Adresse ?? '-' }}</li>
                        <li><strong>Téléphone :</strong> {{ $bondelivraison->fournisseur->Contact ?? '-' }}</li>
                    </ul>
                @endif
            </td>
        </tr>
    </table>

    <!-- DÉTAILS -->
    <div class="details-bon">
        <h5>Détails du bon de livraison</h5>
        <ul>
            <li><strong>Date de livraison :</strong>
                {{ \Carbon\Carbon::parse($bondelivraison->date_livraison)->format('d/m/Y') }}
            </li>
            @if ($bondelivraison->date_reception)
            <li><strong>Date de réception :</strong>
                {{ \Carbon\Carbon::parse($bondelivraison->date_reception)->format('d/m/Y') }}
            </li>
            @endif
        </ul>
    </div>

    <!-- TABLEAU -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th>Type</th>
                    <th>Marque</th>
                    <th>N° Série</th>
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
                    <td colspan="4">Aucun matériel associé</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- INFORMATIONS -->
    <div class="invoice-info">
        <h3>Informations complémentaires</h3>
        <p>{{ $bondelivraison->autre_information ?? 'Aucune information supplémentaire.' }}</p>
    </div>

    <!-- SIGNATURES -->
    <table class="signatures-table">
        <tr>
            <td>
                <h6>Visa du fournisseur</h6>
                <p>Livré le :
                    {{ $bondelivraison->date_livraison ? \Carbon\Carbon::parse($bondelivraison->date_livraison)->format('d/m/Y') : '—' }}
                </p>
                <hr>
                <small>Signature du fournisseur</small>
            </td>

            <td>
                <h6>Visa du client</h6>
                <p>Reçu le :
                    {{ $bondelivraison->date_reception ? \Carbon\Carbon::parse($bondelivraison->date_reception)->format('d/m/Y') : '—' }}
                </p>
                <hr>
                <small>Signature du client</small>
            </td>
        </tr>
    </table>

</div>


