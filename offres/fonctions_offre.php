<?php

require_once "../utils/connexion.php";

function renvoiToutesLesOffres(): array {
    $ptrDB = connexion();

    $query = "SELECT * FROM g19_offre";
    $ptrQuery = pg_query($ptrDB, $query);

    if (!$ptrQuery) {
        exit("Erreur lors de la requête : " . pg_last_error($ptrDB));
    }

    $resu = [];
    while ($row = pg_fetch_assoc($ptrQuery)) {
        $resu[] = $row;
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);

    return $resu;
}


function getOffreById(int $offre_id) : array {
    $ptrDB = connexion();

    $query = "SELECT * FROM g19_offre WHERE offre_id = $1";
    pg_prepare($ptrDB, "reqPrepSelectByOffreId", $query);

    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectByOffreId", array($offre_id));

    $resu = array();

    if (isset($ptrQuery)) {
        while ($row = pg_fetch_assoc($ptrQuery)) {
            $resu[] = $row;
        }
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);

    return $resu;
}

function getNomPlateformeById(int $plat_id) : array {
    $ptrDB = connexion();

    $query = "SELECT plat_nom FROM G19_Plateforme WHERE plat_id = $1"; // Sécurisé
    pg_prepare($ptrDB, "reqPrepSelectByPlatNom", $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectByPlatNom", array($plat_id));

    $resu = pg_fetch_all($ptrQuery) ?: []; // Renvoie un tableau vide si aucune donnée

    pg_free_result($ptrQuery);
    pg_close($ptrDB);

    return $resu;
}

function ajouteUneOffre(array $offre) : array {
    $ptrDB = connexion();

    // Préparer la requête SQL d'insertion
    $query = "INSERT INTO g19_offre (plat_id, offre_nom, offre_prix, offre_code_iso, offre_engagement, offre_audio, offre_video)
              VALUES ($1, $2, $3, $4, $5, $6, $7) RETURNING offre_id";

    pg_prepare($ptrDB, "reqPrepInsertOffre", $query);

    // Exécuter la requête avec les données de l'offre
    $ptrQuery = pg_execute($ptrDB, "reqPrepInsertOffre", array(
        $offre['plat_id'],
        $offre['offre_nom'],
        $offre['offre_prix'],
        $offre['offre_code_iso'],
        $offre['offre_engagement'],
        $offre['offre_audio'],
        $offre['offre_video']
    ));

    // Récupérer l'ID de l'offre insérée
    $newOffreId = pg_fetch_result($ptrQuery, 0, 'offre_id');

    // Libérer les ressources de la requête
    pg_free_result($ptrQuery);
    pg_close($ptrDB);

    // Retourner l'offre insérée avec l'ID généré
    return getOffreById($newOffreId);
}


function updateOffre(array $offre) : array {
    $ptrDB = connexion();

    // Préparer la requête de mise à jour
    $query = "UPDATE g19_offre SET 
                plat_id = $1, 
                offre_nom = $2, 
                offre_prix = $3, 
                offre_code_iso = $4, 
                offre_engagement = $5, 
                offre_audio = $6, 
                offre_video = $7
              WHERE offre_id = $8
              RETURNING offre_id";
    
    pg_prepare($ptrDB, "reqPrepUpdateOffre", $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepUpdateOffre", array(
        $offre['plat_id'],
        $offre['offre_nom'],
        $offre['offre_prix'],
        $offre['offre_code_iso'],
        $offre['offre_engagement'],
        $offre['offre_audio'],
        $offre['offre_video'],
        $offre['offre_id']
    ));

    // Récupérer l'ID de l'offre mise à jour
    $updatedOffreId = pg_fetch_result($ptrQuery, 0, 'offre_id');

    // Libérer les ressources et fermer la connexion
    pg_free_result($ptrQuery);
    pg_close($ptrDB);

    // Retourner l'offre mise à jour
    return getOffreById($updatedOffreId);
}

function deleteOffre(int $plat_id) : array {
    $ptrDB = connexion();

    // Préparer la requête SQL de suppression
    $query = "DELETE FROM g19_offre WHERE offre_id = $1";

    pg_prepare($ptrDB, "reqPrepDeleteOffre", $query);

    // Exécuter la requête de suppression
    $ptrQuery = pg_execute($ptrDB, "reqPrepDeleteOffre", array($plat_id));

    // Libérer les ressources et fermer la connexion
    pg_free_result($ptrQuery);
    pg_close($ptrDB);

    // Retourner un message de confirmation
    return array("message" => "Plateforme avec ID $plat_id supprimée.");
}

?>