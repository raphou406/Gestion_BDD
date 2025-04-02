<?php

require_once 'code.php';

function connexion(){
	include ('code.php');
	$strConnex = "host=$_CODE[Base_de_donne] dbname=$_CODE[Nom] user=$_CODE[Utilisateur] password=$_CODE[Mot_de_passe]";
	$ptrDB = pg_connect($strConnex);
    if (!$ptrDB) exit ('connexion impossible');
	return $ptrDB;
}
?>