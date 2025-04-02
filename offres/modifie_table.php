<?php
require_once '../plateformes/fonctions_plateforme.php';
$plateformes = renvoiToutesLesPlateformes();

require_once 'fonctions_offre.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $offre_id = isset($_GET['offre_id']) ? $_GET['offre_id'] : null;
        $offre = getOffreById($offre_id);
}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $plat_id = isset($_POST['plat_id']) ? $_POST['plat_id'] : null;
    $offre_nom = isset($_POST['offre_nom']) ? $_POST['offre_nom'] : null;
    $offre_prix = isset($_POST['offre_prix']) ? $_POST['offre_prix'] : null;
    $offre_code_iso = isset($_POST['offre_code_iso']) ? $_POST['offre_code_iso'] : 'EUR';
    $offre_engagement = isset($_POST['offre_engagement']) ? $_POST['offre_engagement'] : null;
    $offre_audio = isset($_POST['offre_audio']) ? 't' : 'f';
    $offre_video = isset($_POST['offre_video']) ? 't' : 'f';
    $offre_id = isset($_POST['offre_id']) ? $_POST['offre_id'] : null;

    // Construire le tableau d'offre
    $offre = [
        'plat_id' => $plat_id,
        'offre_id' => $offre_id, 
        'offre_nom' => $offre_nom,
        'offre_prix' => $offre_prix,
        'offre_code_iso' => $offre_code_iso,
        'offre_engagement' => $offre_engagement,
        'offre_audio' => $offre_audio,
        'offre_video' => $offre_video
    ];

    // Appeler la fonction pour ajouter l'offre dans la base de données
    updateOffre($offre);
    header("Location: consultation_table.php");
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification d'une Offre</title>
</head>
<body>
    <h2>Formulaire d'insertion d'une nouvelle offre</h2>
    <form action="modifie_table.php" method="POST">
        <input type="hidden" name="offre_id" value="<?php echo $offre_id; ?>">
        <label for="plat_id">Plat ID:</label>
        <select name="plat_id" id="plat_id">
            <?php 
            //$defaultPlateforme = getNomPlateformeById($offre['plat_id'])[0]['plat_nom'];
            //echo "<option value='$defaultPlateforme' name = 'plat_id'>$defaultPlateforme</option>";
            foreach($plateformes as $plat){
                echo "<option value='$plat[plat_id]' name = 'plat_id'>$plat[plat_nom]</option>";
            }
            ?>
        </select></br><br>

        <label for="offre_nom">Nom de l'offre:</label>
        <input type="text" id="offre_nom" name="offre_nom" value="<?php echo $offre[0]['offre_nom'] ;?>" required><br><br>

        <label for="offre_prix">Prix de l'offre:</label>
        <input type="number" min="0" step="0.01" id="offre_prix" name="offre_prix" value="<?php echo $offre[0]['offre_prix'] ;?>" required><br><br>

        <label for="offre_code_iso">Code ISO (devise):</label>
        <select name = 'offre_code_iso' id = 'offre_code_iso'>
            <option name = 'offre_code_iso' value = 'EUR'>EUR</option>
            <option name = 'offre_code_iso' value = 'USD'>USD</option>;
        </select> </br></br>

        <label for="offre_engagement">Engagement (mois):</label>
        <input type="number" id="offre_engagement" name="offre_engagement" min="0" value="<?php echo $offre[0]['offre_engagement'] ;?>" required><br><br>

        <label for="offre_audio">Offre avec audio:</label>
        <input type="checkbox" id="offre_audio" name="offre_audio" value="TRUE" <?php echo $offre[0]['offre_audio'] == 't' ? 'checked' : '';?> ><br><br>

        <label for="offre_video">Offre avec vidéo:</label>
        <input type="checkbox" id="offre_video" name="offre_video" value="TRUE" <?php echo $offre[0]['offre_video'] == 't' ? 'checked' : '';?> ><br><br>

        <input type="submit" value="Modifier l'offre">
    </form>
</body>
</html>