<?php

require_once "../utils/connexion.php";
require_once "../utils/fonctions_tables.php";

function renvoiTousLesBouquets(): mixed {
    return selectionnerChampsDansTableParUneEgaliteDeChamp("g19_regroupent", array(), null, null);
}


function getBouquetById(int $bouquet_id) : mixed {
    return selectionnerChampsDansTableParUneEgaliteDeChamp("g19_regroupent", array(),  "offre_mere_id", $bouquet_id);
}

function ajouteUnBouquet(array $bouquet) : mixed {
    return insererDansTable("g19_regroupent", $bouquet);
}


function updateBouquet(array $bouquet, array $whereEqualArray) : mixed {
    return mettreAJourDansTable("g19_regroupent", $bouquet, $whereEqualArray );
}

function deleteBouquet(array $bouquet) : mixed {
    return supprimerDansTable("g19_regroupent",  $bouquet);
}
?>