<?php
require_once 'fonctions_plateforme.php';
$plateformes = renvoiToutesLesPlateformes();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les plateformes</title>
</head>
<body>
    <h1>Table des plateformes</h1>

    <h3>ajoute une plateforme</h3>
    <button onclick="window.location.href = 'insertion_table.html';" title="ajoute_plateforme">Ajouter une nouvelle plateforme</button>
    <h3>Toutes les plateformes :</h3>

    <?php if (count($plateformes) > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom de la plateforme</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plateformes as $plat): ?>
                    <tr>
                        <td><?php echo $plat['plat_id']; ?></td>
                        <td><?php echo $plat['plat_nom']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune offre disponible.</p>
    <?php endif; ?>
    <br>
    <button onclick="window.location.href = '../index.php';" title="home">Menu Home</button>
</body>
</html>
