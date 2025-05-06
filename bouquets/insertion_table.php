<?php
require_once '../offres/fonctions_offre.php';
require_once 'fonctions_bouquet.php';
$offres = renvoiToutesLesOffres();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../css/ajoute.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertion d'un Bouquet</title>
</head>
<br>
    <h2>Formulaire d'insertion d'un nouveau Bouquet</h2>
    <form action="inserer_table.php" method="POST">
        <label for="plat_id">Offre Mere:</label>
        <select name="offre_mere_id" id="offre_mere_id">
            
            <option value='new'>--Créer une nouvelle offre--</option>
            
            <?php 
                foreach($offres as $offre){
                    echo "<option value='$offre[offre_id]'>$offre[offre_nom]</option>\n";
                }
                ?>

</select></br><br>

<label for="offre_mer_id">Offre fille :</label>
<?php foreach($offres as $offre):?>
            <label for="<?php echo $offre['offre_nom'];?>">
                <?php
                    echo selectionnerChampsDansTableParUneEgaliteDeChamp(
                        "g19_plateforme",
                        array("plat_nom"), 
                        "plat_id",
                        $offre["plat_id"]
                        )[0]['plat_nom']
                        ." -- ".$offre['offre_nom'];?>
            </label>
            <input type="checkbox" id="Bouquet_audio" name="offre_fille_id[]" value="<?php echo $offre["offre_id"];?>"><br>
        <?php endforeach;?>

        <input type="submit" value="Ajouter l'Bouquet">
    </form>
</body>
</html>
