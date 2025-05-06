<?php
session_start();
$_SESSION["table"] = "g19_offre";
require_once 'fonctions_offre.php';
$offres = renvoiToutesLesOffres();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Offres</title>
</head>
<body>
    <h1>Tables des offres</h1>
    <h3>Ajouter une nouvelle offre</h3>
    <button onclick="window.location.href = 'insertion_table.php';" title="ajoute_offre">Ajouter une nouvelle offre</button>
    <h3>Toutes les offres :</h3>

    <?php if (count($offres) > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom de l'offre</th>
                    <th>Prix</th>
                    <th>Engagement</th>
                    <th>Video</th>
                    <th>Audio</th>
                    <th>Plateforme</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($offres as $offre): ?>
                    <tr>
                        <td><?php echo $offre['offre_id']; ?></td>
                        <td><?php echo $offre['offre_nom']; ?></td>
                        <td><?php echo $offre['offre_prix']; echo $offre['offre_code_iso']?></td>
                        <td><?php echo $offre['offre_engagement']; ?> mois</td>
                        <td><?php echo $offre['offre_video'] == 't'? "Oui" : "Non"; ?></td>
                        <td><?php echo $offre['offre_audio'] == 't'? "Oui" : "Non"; ?></td>
                        <td><?php echo getNomPlateformeById($offre['plat_id'])[0]['plat_nom']; ?></td>
                        <td>
                            <form method="get" action="supprimer_table.php">
                            <input type="hidden" name="offre_id" value="<?php echo $offre['offre_id']; ?>">
                            <button type="submit">Supprimer</button>
                            </form>
                        </td>
                        <td>
                            <form method="get" action="modifier_table.php">
                            <input type="hidden" name="offre_id" value="<?php echo $offre['offre_id']; ?>">
                            <button type="submit">Modifier</button>
                            </form>
                        </td>
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
