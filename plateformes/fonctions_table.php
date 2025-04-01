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

function ajouteUnePlateforme(array $offre) : array {
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

function ajouteUnePlateformeseulementSiElleNExistePas(array $offre) : array {
    $ptrDB = connexion();

    // Vérification si l'offre existe déjà par son nom et plat_id
    $checkQuery = "SELECT * FROM g19_offre WHERE offre_nom = $1 AND plat_id = $2";
    pg_prepare($ptrDB, "reqPrepCheckOffreExists", $checkQuery);
    $checkResult = pg_execute($ptrDB, "reqPrepCheckOffreExists", array($offre['offre_nom'], $offre['plat_id']));
    
    // Si une offre avec les mêmes critères existe déjà, retourner un message d'erreur
    if (pg_num_rows($checkResult) > 0) {
        pg_free_result($checkResult);
        pg_close($ptrDB);
        return array("message" => "Une offre avec le même nom et plat_id existe déjà.");
    }

    // Si l'offre n'existe pas, insérer la nouvelle offre
    $query = "INSERT INTO g19_offre (plat_id, offre_nom, offre_prix, offre_code_iso, offre_engagement, offre_audio, offre_video)
              VALUES ($1, $2, $3, $4, $5, $6, $7) RETURNING offre_id";
    pg_prepare($ptrDB, "reqPrepInsertOffre", $query);
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
    
    // Libérer les ressources et fermer la connexion
    pg_free_result($ptrQuery);
    pg_free_result($checkResult);
    pg_close($ptrDB);

    // Retourner l'offre insérée avec son ID
    return getOffreById($newOffreId);
}


function ajoutePleinDPlateformes(array $Plateformes) : array {
    $ptrDB = connexion();

    // Début de la transaction
    pg_query($ptrDB, "BEGIN");

    // Préparer la requête SQL d'insertion
    $query = "INSERT INTO g19_offre (plat_id, offre_nom, offre_prix, offre_code_iso, offre_engagement, offre_audio, offre_video)
              VALUES ";

    $values = array();
    $params = array();
    $paramIndex = 1;

    // Construire les valeurs et les paramètres pour l'insertion multiple
    foreach ($Plateformes as $offre) {
        $values[] = "($" . $paramIndex++ . ", $" . $paramIndex++ . ", $" . $paramIndex++ . ", $" . $paramIndex++ . ", $" . $paramIndex++ . ", $" . $paramIndex++ . ", $" . $paramIndex++ . ")";
        $params = array_merge($params, [
            $offre['plat_id'],
            $offre['offre_nom'],
            $offre['offre_prix'],
            $offre['offre_code_iso'],
            $offre['offre_engagement'],
            $offre['offre_audio'],
            $offre['offre_video']
        ]);
    }

    $query .= implode(", ", $values);

    pg_prepare($ptrDB, "reqPrepInsertMultiplePlateformes", $query);

    // Exécuter la requête d'insertion
    $ptrQuery = pg_execute($ptrDB, "reqPrepInsertMultiplePlateformes", $params);

    // Si l'insertion réussie, committer la transaction
    if ($ptrQuery) {
        pg_query($ptrDB, "COMMIT");
    } else {
        // Si une erreur se produit, annuler la transaction
        pg_query($ptrDB, "ROLLBACK");
    }

    // Libérer les ressources et fermer la connexion
    pg_free_result($ptrQuery);
    pg_close($ptrDB);

    // Retourner la liste des Plateformes insérées (en fonction des IDs générés)
    // Dans ce cas, on pourrait aussi appeler `getOffreById` pour chaque offre insérée.
    return $Plateformes;
}

function ajouteUneOffreOuLaMetsAJourSuivantCeQuiEstPertinent(array $offre) : array {
    $ptrDB = connexion();

    // Préparer la requête UPSERT (insertion ou mise à jour)
    $query = "INSERT INTO g19_offre (offre_id, plat_id, offre_nom, offre_prix, offre_code_iso, offre_engagement, offre_audio, offre_video)
              VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
              ON CONFLICT (offre_id) 
              DO UPDATE SET plat_id = EXCLUDED.plat_id, offre_nom = EXCLUDED.offre_nom, offre_prix = EXCLUDED.offre_prix, 
                            offre_code_iso = EXCLUDED.offre_code_iso, offre_engagement = EXCLUDED.offre_engagement,
                            offre_audio = EXCLUDED.offre_audio, offre_video = EXCLUDED.offre_video
              RETURNING offre_id";
    
    pg_prepare($ptrDB, "reqPrepUpsertOffre", $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepUpsertOffre", array(
        $offre['offre_id'],
        $offre['plat_id'],
        $offre['offre_nom'],
        $offre['offre_prix'],
        $offre['offre_code_iso'],
        $offre['offre_engagement'],
        $offre['offre_audio'],
        $offre['offre_video']
    ));

    // Récupérer l'ID de l'offre insérée ou mise à jour
    $updatedOffreId = pg_fetch_result($ptrQuery, 0, 'offre_id');

    // Libérer les ressources et fermer la connexion
    pg_free_result($ptrQuery);
    pg_close($ptrDB);

    // Retourner l'offre mise à jour ou insérée
    return getOffreById($updatedOffreId);
}
?>