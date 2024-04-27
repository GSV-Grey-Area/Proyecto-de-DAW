<?php

// Recoge el ID de la categoría seleccionada.
if(isset($_GET['id'])) { // Cambiado 'ID' a 'id'
    $categoriaID = $_GET['id']; // Cambiado 'ID' a 'id'
    echo "ID de la categoria es:  ".$categoriaID;
} else {
    echo "No ha llegado el ID";
}
?>