<?php

require_once "../utils/connexion.php";
require_once "../utils/fonctions_tables.php";

function renvoiToutesLesOffres(): mixed {
    return selectionnerChampsDansTableParUneEgaliteDeChamp("g19_offre", array(), null, null);
}

function getOffreById(int $offre_id) : mixed {
    return selectionnerChampsDansTableParUneEgaliteDeChamp("g19_offre", array(),  "offre_id", $offre_id);
}

function getNomPlateformeById(int $plat_id) : mixed {
    return selectionnerChampsDansTableParUneEgaliteDeChamp("G19_Plateforme", ["plat_nom"], "plat_id", $plat_id);
}

function ajouteUneOffre(array $offre) : mixed {
    return insererDansTable("g19_offre", $offre);
}


function updateOffre(array $offre) : mixed {
    return mettreAJourDansTable("g19_offre", $offre, ["offre_id" => $offre["offre_id"]]);
}

function deleteOffre(int $offre_id) : mixed {
    return supprimerDansTable("g19_offre", ["offre_id" => $offre_id]);
}

?>