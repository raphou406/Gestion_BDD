<?php
require_once '../offres/fonctions_offre.php';
$offres = renvoiToutesLesOffres();

require_once 'fonctions_bouquet.php';

// si on recoit un get alors on propose le menu de modification(l'utilisateur a donc cliqué sur modifier, sur une ligne d'un bouquet, dans la page de consultation des bouquets)
// si c'est un post on modifie reellement (dans ce cas l'utilisateur etait deja dans le menu de modification d'une ligne de bouquet, et a confirmé sa modification, on l'actualise et on redirige vers la page de consultation)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $offre_mere_id = isset($_GET['bouquet_mere_id']) ? $_GET['bouquet_mere_id'] : null;
        $offre_fille_id = isset($_GET['bouquet_fille_id']) ? $_GET['bouquet_fille_id'] : null;

        foreach ($offres as $offre){
            if ($offre['offre_id'] == $offre_mere_id){
                $offre_mere = $offre;
                break;
            }
        }
}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $offre_mere_id = isset($_POST['offre_mere_id']) ? $_POST['offre_mere_id'] : null;
    $offre_fille_id = isset($_POST['offre_fille_id']) ? $_POST['offre_fille_id'] : null;
    $new_offre_mere_id = isset($_POST['new_offre_mere_id']) ? $_POST['new_offre_mere_id'] : null;
    $new_offre_fille_id = isset($_POST['new_offre_fille_id']) ? $_POST['new_offre_fille_id'] : null;
    //print_r($_POST);
    // Construire le tableau d'Bouquet
    $Bouquet = [
        'offre_mere_id' => $new_offre_mere_id,
        'offre_fille_id' => $new_offre_fille_id, 
    ];

    updateBouquet($Bouquet, ["offre_mere_id" =>  $offre_mere_id, "offre_fille_id" => $offre_fille_id]);
    
    echo pg_last_error();
    header("Location: consultation_table.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../css/ajoute.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de bouquet</title>
</head>
<br>
    <h2>Formulaire de modification de bouquet</h2>
    <form action="modifier_table.php" method="POST">
        <label for="plat_id">offre Mere:</label>

        <input type="hidden" name="offre_mere_id" value="<?php echo $offre_mere_id; ?>">
        <input type="hidden" name="offre_fille_id" value="<?php echo $offre_fille_id; ?>">

        <label>Offre fille</label>

        <select name="new_offre_mere_id" id="new_offre_mere_id">

            <?php foreach($offres as $offre):?>
                    <?php 
                        echo "<option value='$offre[offre_id]'"
                        .(($offre['offre_id'] == $offre_mere_id)?"selected>":">")
                        .selectionnerChampsDansTableParUneEgaliteDeChamp(
                            "g19_plateforme",
                            array("plat_nom"), 
                            "plat_id",
                            $offre["plat_id"]
                            )[0]['plat_nom']
                            ." -- ".$offre['offre_nom']
                            ."</option>\n";
                    ?>
            <?php endforeach;?>

        </select></br><br>

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
            <input type="radio" id="new_offre_fille_id" name="new_offre_fille_id" value=<?php echo "\"".$offre["offre_id"]."\" ".(($offre['offre_id'] == $offre_fille_id)?"checked":"");?>><br>
        <?php endforeach;?>

        <input type="submit" value="modifier le bouquet">
    </form>
</body>
</html>