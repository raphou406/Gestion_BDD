<?php

require 'code.php';

function connexion(){
	include ('code.php');
	$strConnex = "host=$_CODE[Base_de_donne] dbname=$_CODE[Nom] user=$_CODE[Utilisateur] password=$_CODE[Mot_de_passe]";
	$ptrDB = pg_connect($strConnex);
    if (!$ptrDB) exit ('connexion impossible');
	return $ptrDB;
}


function renvoiToutesLesPlateformes(): array {
    $ptrDB = connexion();

    $query = "SELECT * FROM g19_plateforme";
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

function getPlateformeById(int $plat_id) : array {
    $ptrDB = connexion();

    $query = "SELECT * FROM g19_plateforme WHERE plat_id = $1";
    pg_prepare($ptrDB, "reqPrepSelectByPlatId", $query);

    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectByPlatId", array($plat_id));

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

function ajouteUnePlateforme(array $plateforme) : array {
    $ptrDB = connexion();

    // Préparer la requête SQL d'insertion
    $query = "INSERT INTO g19_plateforme (plat_nom)
              VALUES ($1) RETURNING plat_id";

    pg_prepare($ptrDB, "reqPrepInsertPlateforme", $query);

    // Exécuter la requête avec les données de la plateforme
    $ptrQuery = pg_execute($ptrDB, "reqPrepInsertPlateforme", array(
        $plateforme['plat_nom']
    ));

    // Récupérer l'ID de la plateforme insérée
    $newPlatId = pg_fetch_result($ptrQuery, 0, 'plat_id');

    // Libérer les ressources de la requête
    pg_free_result($ptrQuery);
    pg_close($ptrDB);

    // Retourner la plateforme insérée avec l'ID généré
    return getPlateformeById($newPlatId);
}

function updatePlateforme(array $plateforme) : array {
    $ptrDB = connexion();

    // Préparer la requête de mise à jour
    $query = "UPDATE g19_plateforme
              SET plat_nom = $1
              WHERE plat_id = $2";

    pg_prepare($ptrDB, "reqPrepUpdatePlateforme", $query);

    // Exécuter la requête avec les nouvelles données
    $ptrQuery = pg_execute($ptrDB, "reqPrepUpdatePlateforme", array(
        $plateforme['plat_nom'],
        $plateforme['plat_id']
    ));

    // Libérer les ressources
    pg_free_result($ptrQuery);
    pg_close($ptrDB);

    // Retourner la plateforme mise à jour
    return getPlateformeById($plateforme['plat_id']);
}

function deletePlateforme(int $plat_id) : array {
    $ptrDB = connexion();

    // Préparer la requête SQL de suppression
    $query = "DELETE FROM g19_plateforme WHERE plat_id = $1";

    pg_prepare($ptrDB, "reqPrepDeletePlateforme", $query);

    // Exécuter la requête de suppression
    $ptrQuery = pg_execute($ptrDB, "reqPrepDeletePlateforme", array($plat_id));

    // Libérer les ressources et fermer la connexion
    pg_free_result($ptrQuery);
    pg_close($ptrDB);

    // Retourner un message de confirmation
    return array("message" => "Plateforme avec ID $plat_id supprimée.");
}

?>