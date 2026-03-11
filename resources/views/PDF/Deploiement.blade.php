<style>
/* ===== FORMAT A4 DOMPDF ===== */
@page {
    size: A4;
    margin: 2cm 2cm 2.5cm 2cm;
}

/* ===== RESET ===== */
body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 14px; /* texte lisible */
    color: #000;
    margin: 0;
    padding: 0;
    line-height: 1.4;
}

/* ===== CONTENEUR ===== */
.fiche-container {
    width: 100%;
}

/* ===== LOGO ===== */
.logo-container {
    text-align: center;
    margin-bottom: 15px;
}

.logo-container img {
    height: 80px; /* logo plus visible */
}

/* ===== HEADER JAUNE ===== */
.header-table {
    width: 100%;
    background-color: #FDCE62; /* jaune BMS */
    border-bottom: 2px solid #000;
    margin-bottom: 20px;
}

.header-table td {
    vertical-align: top;
    font-size: 14px;
    padding: 10px 12px;
    font-weight: bold;
}

/* ===== TITRE ===== */
h2 {
    text-align: center;
    margin: 20px 0 25px;
    text-transform: uppercase;
    font-size: 20px; /* titre plus visible */
    font-weight: bold;
}

/* ===== TABLES ===== */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

th, td {
    border: 1px solid #000;
    padding: 8px;
    font-size: 14px; /* texte lisible */
    text-align: center;
    vertical-align: middle;
}

th {
    background: #f2f2f2;
    font-weight: bold;
}

/* ===== FOOTER DATE ===== */
.footer-date {
    margin-top: 25px;
    font-size: 14px;
    font-weight: bold;
}

/* ===== SIGNATURE ===== */
.signature-table {
    width: 100%;
    margin-top: 50px;
}

.signature-table td {
    width: 50%;
    text-align: center;
    padding-top: 55px;
    font-weight: bold;
    font-size: 14px;
    border: none;
}

.signature-line {
    display: inline-block;
    width: 85%;
    border-top: 1.5px solid #000;
    margin-top: 45px;
}

/* ===== COMMENTAIRES ===== */
.commentaires {
    margin-top: 40px;
    font-weight: bold;
    text-align: center;
    font-size: 15px;
}
</style>

<div class="fiche-container">

    {{-- LOGO --}}
    <div class="logo-container">
        <img src="{{ public_path('assets/img/bms.jpg') }}" alt="Logo BMS">
    </div>

    {{-- HEADER JAUNE --}}
    <table class="header-table">
        <tr>
            <td>
                BMS S.A<br>
                Banque Malienne de Solidarité
            </td>
            <td style="text-align:right;">
                Réf : Fiche de déploiement<br>
                Version : 2.0<br>
                DSI
            </td>
        </tr>
    </table>

    {{-- TITRE --}}
    <h2>Fiche de déploiement</h2>

    {{-- INFORMATIONS UTILISATEUR --}}
    <table>
        <tr>
            <th>Agence / Direction</th>
            <td>{{ $deploiement->Direction ?? '-' }}</td>
        </tr>
        <tr>
            <th>Utilisateur</th>
            <td>{{ $deploiement->Utilisateur ?? '-' }}</td>
        </tr>
        <tr>
            <th>Poste</th>
            <td>{{ $deploiement->Poste ?? '-' }}</td>
        </tr>
    </table>

    {{-- MATÉRIEL --}}
    <table>
        <tr>
            <th>Désignation</th>
            <th>Marque</th>
            <th>Type</th>
            <th>N° Série</th>
        </tr>

        @foreach ($deploiement->ligneDeploiements as $ligne)
            @php $mat = optional($ligne->lignebondelivraison)->materiel; @endphp
            <tr>
                <td>{{ $mat->designation ?? '-' }}</td>
                <td>{{ optional($mat->marque)->Designation ?? '-' }}</td>
                <td>{{ optional($mat->typemateriel)->Designation ?? '-' }}</td>
                <td>{{ $mat->numero_serie ?? '-' }}</td>
            </tr>
        @endforeach
    </table>

    {{-- CONFIGURATION --}}
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

    {{-- DATE --}}
    <div class="footer-date">
        Bamako, le {{ \Carbon\Carbon::parse($deploiement->Datecreation)->format('d/m/Y') }}
    </div>

    {{-- SIGNATURES --}}
    <table class="signature-table">
        <tr>
            <td>
                Agent DSI
                <div class="signature-line"></div>
            </td>
            <td>
                Utilisateur
                <div class="signature-line"></div>
            </td>
        </tr>
    </table>

    {{-- COMMENTAIRES --}}
    <div class="commentaires">
        COMMENTAIRES
    </div>

</div>
