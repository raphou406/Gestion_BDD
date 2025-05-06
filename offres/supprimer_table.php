<?php
require_once 'fonctions_offre.php';

$offre_id = isset($_GET['offre_id']) ? $_GET['offre_id'] : null;
deleteOffre($offre_id);
header("Location: consultation_table.php");
?>
