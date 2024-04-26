<?php

require "AccessDB.php";

//Recoge el ID de la categoria seleccionada.
if(isset($_GET['categoriaID'])) {
    $categoriaID = $_GET['categoriaID'];
    echo $categoriaID;
} else {
    console.log("No se ha mandado la categoria.");
}
?>