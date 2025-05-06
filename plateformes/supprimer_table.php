<?php
require_once 'fonctions_plateforme.php';

deletePlateforme($_GET['plat_id']);
header("Location: consultation_table.php");
?>
