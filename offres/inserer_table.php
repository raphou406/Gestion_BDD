<?php
require_once 'fonctions_offre.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $plat_id = isset($_POST['plat_id']) ? $_POST['plat_id'] : null;
    $offre_nom = isset($_POST['offre_nom']) ? $_POST['offre_nom'] : null;
    $offre_prix = isset($_POST['offre_prix']) ? $_POST['offre_prix'] : null;
    $offre_code_iso = isset($_POST['offre_code_iso']) ? $_POST['offre_code_iso'] : 'EUR';
    $offre_engagement = isset($_POST['offre_engagement']) ? $_POST['offre_engagement'] : null;
    $offre_audio = isset($_POST['offre_audio']) ? true : false;
    $offre_video = isset($_POST['offre_video']) ? true : false;

    // Construire le tableau d'offre
    $offre = [
        'plat_id' => $plat_id,
        'offre_nom' => $offre_nom,
        'offre_prix' => $offre_prix,
        'offre_code_iso' => $offre_code_iso,
        'offre_engagement' => $offre_engagement,
        'offre_audio' => $offre_audio,
        'offre_video' => $offre_video
    ];

    // Appeler la fonction pour ajouter l'offre dans la base de données
    ajouteUneOffre($offre);
}
header("Location: consultation_table.php");
?>
