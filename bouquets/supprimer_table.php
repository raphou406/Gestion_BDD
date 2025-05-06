<?php
require_once 'fonctions_bouquet.php';
$bouquet = array(
    'offre_mere_id' => $_GET['bouquet_mere_id'][0],
    'offre_fille_id' => $_GET['bouquet_fille_id'][0]
);
deleteBouquet($bouquet);
header("Location: consultation_table.php");
?>
