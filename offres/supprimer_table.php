<?php
require_once 'fonctions_offre.php';

deleteOffre($_GET['offre_id']);
header("Location: consultation_table.php");
?>
