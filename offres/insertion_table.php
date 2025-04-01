<?php
require '../plateformes/fonctions_table.php';
$plateformes = renvoiToutesLesPlateformes();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertion d'une Offre</title>
</head>
<br>
    <h2>Formulaire d'insertion d'une nouvelle offre</h2>
    <br action="inserer_table.php" method="POST">
        <label for="plat_id">Plat ID:</label>
        <select name="plat_id" id="plat_id">
            <?php 
            foreach($plat as $plateformes){
                $nom = $offre['plat_nom'];
                echo ($nom);
                echo "<option value='$nom' name = 'plat_nom'>$nom</option>";
            }
            ?>
        </select></br></br>

        <label for="offre_nom">Nom de l'offre:</label>
        <input type="text" id="offre_nom" name="offre_nom" required><br><br>

        <label for="offre_prix">Prix de l'offre:</label>
        <input type="number" min="0" step="0.01" id="offre_prix" name="offre_prix" required><br><br>

        <label for="offre_code_iso">Code ISO (devise):</label>
        <select name = 'offre_code_iso' id = 'offre_code_iso'>
            <option name = 'offre_code_iso' value = 'EUR'>EUR</option>
            <option name = 'offre_code_iso' value = 'USD'>USD</option>;
        </select> </br></br>

        <label for="offre_engagement">Engagement (mois):</label>
        <input type="number" id="offre_engagement" name="offre_engagement" min="0" required><br><br>

        <label for="offre_audio">Offre avec audio:</label>
        <input type="checkbox" id="offre_audio" name="offre_audio" value="TRUE"><br><br>

        <label for="offre_video">Offre avec vidéo:</label>
        <input type="checkbox" id="offre_video" name="offre_video" value="TRUE"><br><br>

        <input type="submit" value="Ajouter l'offre">
    </form>
</body>
</html>
