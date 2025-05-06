<?php
require_once "../utils/connexion.php";
require_once "../utils/fonctions_tables.php";

function renvoiToutesLesPlateformes(): mixed {
    return selectionnerChampsDansTableParUneEgaliteDeChamp("g19_plateforme", array(), null, null);
}

function getPlateformeById(int $plat_id) : mixed {
    return selectionnerChampsDansTableParUneEgaliteDeChamp("g19_plateforme", array(),  "plat_id", $plat_id);
}

function ajouteUnePlateforme(array $plat) : mixed {
    return insererDansTable("g19_plateforme", $plat);
}


function updatePlateforme(array $plat) : mixed {
    return mettreAJourDansTable("g19_plateforme", $plat, ["plat_id" => $plat["plat_id"]]);
}

function deletePlateforme(int $plat_id) : mixed {
    return supprimerDansTable("g19_plateforme", ["plat_id" => $plat_id]);
}
?>