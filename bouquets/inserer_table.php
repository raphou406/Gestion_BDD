<?php
require_once 'fonctions_bouquet.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $offre_mere_id = isset($_POST['offre_mere_id']) ? $_POST['offre_mere_id'] : null;
    $offre_fille_id_list = isset($_POST['offre_fille_id']) ? $_POST['offre_fille_id'] : null;

    if ($offre_mere_id == 'new'){
        header("Location: ../offres/insertion_table.php");
    }
    // Construire le tableau d'Bouquet
    foreach($offre_fille_id_list as $fille){
        if($offre_mere_id == $fille)continue;
        $bouquet = [
            'offre_mere_id' => $offre_mere_id,
            'offre_fille_id' => $fille
        ];
        // Appeler la fonction pour ajouter l'Bouquet dans la base de données
        ajouteUnBouquet($bouquet);
    }
}
if ($offre_mere_id != "new"){
    header("Location: consultation_table.php", true);
}
?>
