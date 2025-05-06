<?php
require_once 'fonctions_plateforme.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $plat_nom = isset($_POST['plat_nom']) ? $_POST['plat_nom'] : null;

    // Construire le tableau d'offre
    $plateforme = [
        'plat_nom' => $plat_nom
    ];

    // Appeler la fonction pour ajouter l'offre dans la base de données
    $result = ajouteUnePlateforme($plateforme);

    // pour le debug
    if (isset($result['offre_id'])) {
        echo "L'offre a été ajoutée avec succès. ID de l'offre: " . $result['offre_id'];
    } else {
        echo "Erreur lors de l'ajout de l'offre.";
    }
}
header("Location: consultation_table.php");
?>
