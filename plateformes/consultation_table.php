<?php
require 'fonctions_table.php';
$offres = renvoiToutesLesOffres();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les Offres</title>
</head>
<body>
    <h1>Consulter les offres</h1>
    <h3>Toutes les offres :</h3>

    <?php if (count($offres) > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom de l'offre</th>
                    <th>Prix</th>
                    <th>Engagement</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($offres as $offre): ?>
                    <tr>
                        <td><?php echo $offre['offre_id']; ?></td>
                        <td><?php echo $offre['offre_nom']; ?></td>
                        <td><?php echo $offre['offre_prix']; ?> €</td>
                        <td><?php echo $offre['offre_engagement']; ?> mois</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune offre disponible.</p>
    <?php endif; ?>
</body>
</html>
