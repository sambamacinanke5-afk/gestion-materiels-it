<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif">

    <h3>Bonjour {{ $bondelivraison->user->name ?? 'Utilisateur' }},</h3>

    <p>Un <strong>nouveau Bon de Livraison</strong> vient d’être créé dans le système.</p>

    <ul>
        <li><strong>Référence :</strong> {{ $bondelivraison->bondelivraison }}</li>
        <li><strong>Date livraison :</strong> {{ $bondelivraison->date_livraison }}</li>
        <li><strong>Fournisseur :</strong> {{ $bondelivraison->fournisseur->nom ?? '-' }}</li>
    </ul>

    <p>Veuillez vous connecter à l’application pour consulter les détails.</p>

    <p>
        Cordialement,<br>
        <strong>Système de gestion IT – BMS</strong>
    </p>

</body>
</html>
