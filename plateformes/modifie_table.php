<?php
require_once 'fonctions_plateforme.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $plat_id = isset($_GET['plat_id']) ? $_GET['plat_id'] : null;
        $plat = getPlateformeById($plat_id);
}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $plat_id = isset($_POST['plat_id']) ? $_POST['plat_id'] : null;
    $plat_nom = isset($_POST['plat_nom']) ? $_POST['plat_nom'] : null;


    // Construire le tableau d'offre
    $plateforme = [
        'plat_id' => $plat_id,
        'plat_nom' => $plat_nom
    ];

    // Appeler la fonction pour ajouter l'offre dans la base de données
    updatePlateforme($plateforme);
    header("Location: consultation_table.php");
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../css/ajoute.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification d'une Offre</title>
</head>
<body>
    <h2>Formulaire de modification d'une plateforme</h2>
    <form action="modifie_table.php" method="POST">
        <input type="hidden" name="plat_id" id="plat_id" value="<?php echo $plat_id; ?>">

        <label for="plat_nom">Nouveau nom de la plateforme</label>
        <input type="text" id="plat_nom" name="plat_nom" value="<?php echo $plat[0]["plat_nom"];?>"required><br><br>

        <input type="submit" value="Modifier l'offre">
    </form>
</body>
</html>