<?php
session_start();
$_SESSION["table"] = "g19_regroupent";
require_once 'fonctions_bouquet.php';
$bouquets = renvoiTousLesBouquets();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bouquets</title>
</head>
<body>
    <h1>Tables des Bouquets</h1>
    <h3>Ajouter un nouveau Bouquet</h3>
    <button onclick="window.location.href = 'insertion_table.php';" title="ajoute_bouquet">Ajouter un nouveau bouquet</button>
    <h3>Tous les bouquets :</h3>

    <?php if (count($bouquets) > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Mere</th>
                    <th>Fille</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bouquets as $bouquet): ?>
                    <tr>
                        
                        <td><?php echo selectionnerChampsDansTableParUneEgaliteDeChamp(
                                'g19_offre',
                                array('offre_nom'),
                                'offre_id', 
                                $bouquet['offre_mere_id']
                            )[0]["offre_nom"]; 
                            ?></td>
                        <td><?php echo selectionnerChampsDansTableParUneEgaliteDeChamp(
                                'g19_offre',
                                array('offre_nom'),
                                'offre_id', 
                                $bouquet['offre_fille_id']
                            )[0]["offre_nom"]; 
                            ?></td>
                        <td>
                            <form method="get" action="supprimer_table.php">
                            <input type="hidden" name="bouquet_mere_id[]" value="<?php echo $bouquet['offre_mere_id']; ?>">
                            <input type="hidden" name="bouquet_fille_id[]" value="<?php echo $bouquet['offre_fille_id']; ?>">
                            <button type="submit">Supprimer</button>
                            </form>
                        </td>
                        <td>
                            <form method="get" action="modifier_table.php">
                            <input type="hidden" name="bouquet_mere_id" value="<?php echo $bouquet['offre_mere_id']; ?>">
                            <input type="hidden" name="bouquet_fille_id" value="<?php echo $bouquet['offre_fille_id']; ?>">
                            <button type="submit">Modifier</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun bouquet disponible.</p>
    <?php endif; ?>
    <br>
    <button onclick="window.location.href = '../index.php';" title="home">Menu Home</button>
</body>
</html>
