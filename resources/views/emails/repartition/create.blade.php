<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle Répartition</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f5f6f8;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: #ffffff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .header {
            background: #0d6efd;
            color: #fff;
            padding: 15px;
            text-align: center;
        }

        .content {
            padding: 20px;
        }

        .repartition {
            color: #d10000;
            font-weight: bold;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }

        table th {
            background-color: #f0f0f0;
            text-align: left;
        }

        .footer {
            background: #f0f0f0;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>📦 Nouvelle Répartition de Matériel</h2>
    </div>

    <div class="content">
        <p>Bonjour Administrateur,</p>

        <p>
            Une nouvelle répartition de matériel a été effectuée avec la référence :
        </p>

        <p class="repartition">
            {{ $repartition->repartition }}
        </p>

        <p>
            <strong>Date de répartition :</strong>
            {{ \Carbon\Carbon::parse($repartition->date_repartition)->format('d/m/Y') }}
        </p>

        <p>
            <strong>Service concerné :</strong>
            {{ optional($repartition->service)->Designation ?? '—' }}
        </p>

        <h4>Détails des matériels répartis :</h4>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Matériel</th>
                    <th>Destinataire</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($repartition->lignes as $index => $ligne)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ optional($ligne->materiel)->designation ?? 'N/A' }}
                            <br>
                            <small>
                                SN :
                                {{ optional($ligne->materiel)->numero_serie ?? '—' }}
                            </small>
                        </td>
                        <td>
                            {{ optional($ligne->destinataire)->name ?? '—' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top: 20px;">
            Merci de vous connecter à l’application pour plus de détails.
        </p>

        <p>
            <strong>Gestion IT – BMS</strong>
        </p>
    </div>

    <div class="footer">
        Cet email a été généré automatiquement — merci de ne pas répondre.
    </div>
</div>

</body>
</html>
