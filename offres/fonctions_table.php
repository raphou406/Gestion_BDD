<?php

require 'code.php';

function connexion(){
	include ('code.php');
	$strConnex = "host=$_CODE[Base_de_donne] dbname=$_CODE[Nom] user=$_CODE[Utilisateur] password=$_CODE[Mot_de_passe]";
	$ptrDB = pg_connect($strConnex);
    if (!$ptrDB) exit ('connexion impossible');
	return $ptrDB;
}

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


function getOffreById(int $plat_id) : array {
    $ptrDB = connexion();

    $query = "SELECT * FROM g19_offre WHERE plat_id = $1";
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

function getNomPlateformeById(int $plat_id) : array {
    $ptrDB = connexion();

    $query = "SELECT plat_nom FROM G19_Plateforme WHERE plat_id = $1"; // Sécurisé
    pg_prepare($ptrDB, "reqPrepSelectByPlatId", $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectByPlatId", array($plat_id));

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

function ajouteNouvelleOffre(array $offre) : array {
    $ptrDB = connexion();

    // Vérification si l'offre existe déjà avec tous ses attributs
    $checkQuery = "SELECT offre_id FROM g19_offre 
                   WHERE plat_id = $1 
                   AND offre_nom = $2 
                   AND offre_prix = $3 
                   AND offre_code_iso = $4
                   AND offre_engagement = $5
                   AND offre_audio = $6
                   AND offre_video = $7";
    pg_prepare($ptrDB, "reqPrepCheckOffreExists", $checkQuery);
    $checkResult = pg_execute($ptrDB, "reqPrepCheckOffreExists", array(
        $offre['plat_id'],
        $offre['offre_nom'],
        $offre['offre_prix'],
        $offre['offre_code_iso'],
        $offre['offre_engagement'],
        $offre['offre_audio'],
        $offre['offre_video']
    ));

    // Si une offre avec les mêmes critères existe déjà, retourner un message d'erreur
    if (pg_num_rows($checkResult) > 0) {
        pg_free_result($checkResult);
        pg_close($ptrDB);
        return array("message" => "Une offre identique existe déjà.");
    }

    // Si l'offre n'existe pas, insérer la nouvelle offre
    return ajouteUneOffre($offre);
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

?>