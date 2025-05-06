<?php
require_once "connexion.php";
function selectionnerChampsDansTableParUneEgaliteDeChamp(string $table, array $champsSelection, ?string $champWhere, ?string $valeurWhere): array {
    // Connexion à la base de données
    $ptrDB = connexion();

    // Vérification des champs à sélectionner
    $champs = (empty($champsSelection)) ? "*" : implode(", ", $champsSelection);

    // Construction de la requête SQL
    $query = "SELECT $champs FROM $table"; // On insère directement le nom de la table

    $params = [];
    if (!empty($champWhere) && !empty($valeurWhere)) {
        $query .= " WHERE $champWhere = $1";
        $params[] = $valeurWhere;
    }

    // Exécution directe (pas besoin de pg_prepare si on ne réutilise pas la requête)
    $ptrQuery = pg_query_params($ptrDB, $query, $params);
    
    $resu = [];
    if ($ptrQuery) {
        while ($ligne = pg_fetch_assoc($ptrQuery)) {
            $resu[] = $ligne;
        }
        pg_free_result($ptrQuery);
    }

    pg_close($ptrDB);

    return empty($resu) ? ["message" => "Aucun résultat pour la sélection de tous les éléments de $table"] : $resu;
}


function insererDansTable(string $table, array $element):mixed {
    $ptrDB = connexion();

    if (empty($element)) {
        return false;
    }

    $champs = array_keys($element);
    $valeurs = array_values($element);
    // element sous forme champ => valeur
    $emplacementsChamps = [];
    foreach ($champs as $i => $champ) {
        $emplacementsChamps[] = $champ;
    }
    $emplacementsValeurs = [];
    foreach ($valeurs as $i => $value) {
        $emplacementsValeurs[] = '$'.($i + 1);
    }

    $query = "INSERT INTO $table (".implode(", ", $emplacementsChamps).") 
              VALUES (".implode(", ", $emplacementsValeurs).") RETURNING *";

    $reqPrepNom = "reqPrepInsererDans_".$table."_".uniqid();
    $result = pg_prepare($ptrDB, $reqPrepNom, $query);
    if (!$result) {
        return false;
    }


    $ptrQuery = pg_execute($ptrDB, $reqPrepNom, $valeurs);
    if (!$ptrQuery) {
        return false;
    }

    $ligne = pg_fetch_assoc($ptrQuery);
    if ($ligne) {
        return $ligne;
    }
    return false;
}

function mettreAJourDansTable(string $table, array $element, array $whereEqualArray): mixed {
    // ICI ICI ICI ICI ICI ICI 
    //TODO POUR HENRI : implementer la fonction ici en se basant sur supprimer avec le wherequalarray, copie de la requete du update precedent entre les V
    $ptrDB = connexion();

    
    $champs = array_keys($element);
    $valeurs = array_values($element);

    $champWheres = array_keys($whereEqualArray);
    $valeurWheres = array_values($whereEqualArray);

    $conditionsArray = [];
    $emplacementsChamps = [];


    $nextVarInt = 1;
    foreach ($champs as $i => $champ) {
        $emplacementsChamps[] = $champ.' = $'.$nextVarInt++;
    }
    foreach($champWheres as $i => $champWhere){
        $conditionsArray[] = "$champWhere = $".$nextVarInt++;
    }

    $query = "UPDATE $table SET ".implode(", ", $emplacementsChamps);
    if(isset($whereEqualArray) && count( $whereEqualArray) !=0){
        $query .= " WHERE ".implode(" AND ", $conditionsArray);
    }
    $query .= " RETURNING *";
    
    $reqPrepNom = "reqPrepModifierDans_".$table."_".uniqid();
    $result = pg_prepare($ptrDB, $reqPrepNom, $query);

    if (!$result) {
        return false;
    }
    if(isset($whereEqualArray) && count( $whereEqualArray) !=0){
        $ptrQuery = pg_execute($ptrDB, $reqPrepNom, array_merge($valeurs, $valeurWheres));
    }
    else{
        $ptrQuery = pg_execute($ptrDB, $reqPrepNom, array($valeurs));
    }
    if (!$ptrQuery) {
        return false;
    }

    $row = pg_fetch_assoc($ptrQuery);
    if ($row) {
        return $row;
    }
    pg_close($ptrDB);
    return false;
}
/*
function mettreAJourDansTable(string $table, array $element, string $champWhere, string $valeurWhere): mixed {
    $ptrDB = connexion();

    if (empty($element)) {
        return false;
    }
    
    $champs = array_keys($element);
    $valeurs = array_values($element);

    $emplacementsChamps = [];
    foreach ($champs as $i => $champ) {
        $emplacementsChamps[] = $champ.' = $'.$i + 1;
    }
    print_r($emplacementsChamps);
    $query = "UPDATE $table SET ".implode(", ", $emplacementsChamps);

    if(isset($champWhere) && isset($valeurWhere)){
        $query .= " WHERE $champWhere = $".count($champs) + 1;
    }
    $reqPrepNom = "reqPrepMettreAJour_".$table."_".uniqid();
    $result = pg_prepare($ptrDB, $reqPrepNom, $query);
    
    $query .= " RETURNING *";
    if (!$result) {
        return false;
    }
    if(isset($champWhere) && isset($valeurWhere)){
        $ptrQuery = pg_execute($ptrDB, $reqPrepNom, [...$valeurs, $valeurWhere]);
    }
    else{
        $ptrQuery = pg_execute($ptrDB, $reqPrepNom, array($valeurs));
    }
    
    if (!$ptrQuery) {
        return false;
    }
    
    $ligne = pg_fetch_assoc($ptrQuery);
    if ($ligne) {
        return $ligne;
    }
    pg_close($ptrDB);
    return false;
}

 */
function supprimerDansTable(string $table, array $whereEqualArray): mixed {
    $ptrDB = connexion();
    $query = "DELETE FROM $table";

    $champWheres = array_keys($whereEqualArray);
    $valeurWheres = array_values($whereEqualArray);
    $conditionsArray = [];
    foreach($champWheres as $i => $champWhere){
        $conditionsArray[] = "$champWhere = $".$i+1;
    }
    if(isset($whereEqualArray) && count( $whereEqualArray) !=0){
        $query .= " WHERE ".implode(" AND ", $conditionsArray);
    }
    $query .= " RETURNING *";
    echo $query;
    $reqPrepNom = "reqPrepSupprimerDans_".$table."_".uniqid();
    $result = pg_prepare($ptrDB, $reqPrepNom, $query);

    if (!$result) {
        return false;
    }
    if(isset($whereEqualArray) && count( $whereEqualArray) !=0){
        $ptrQuery = pg_execute($ptrDB, $reqPrepNom, $valeurWheres);
    }
    else{
        $ptrQuery = pg_execute($ptrDB, $reqPrepNom, array());
    }
    if (!$ptrQuery) {
        return false;
    }

    $row = pg_fetch_assoc($ptrQuery);
    if ($row) {
        return $row;
    }
    pg_close($ptrDB);
    return false;
}