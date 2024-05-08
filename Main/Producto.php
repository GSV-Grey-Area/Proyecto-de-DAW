<?php
require "AccessDB.php";
// Recoge el ID de la categoría seleccionada.
if(isset($_GET['Nombre'])) { 
    $nombre = $_GET['Nombre']; 
} else {
    echo "No ha llegado el ID";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="CSSindex.css">
    <style>
        #Cuerpo
        {
            display:flex;
            flex-direction:row;
            margin-left:25%;
            margin-top:5%;
        }
        #Descripcion
        {
            margin-left:15%;
            margin-right:15%;
        }
        #Precio
        {
            font-size:30px;
        }
    </style>
</head>
<body>
    <header>    
        <div id="logo">
            <img src="./img/logo128.png" alt="logo">
        </div>
        <hr>
        <div id="Menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="">Ayuda</a></li>
                <li><a href="">Info</a></li>
                <li><a href="">Cuenta</a></li>
                <li><a href="">Cesta</a></li>
                <li><a href="Admin.php">Admin</a></li>
            </ul>
        </div>
        <hr>
    </header>
    <?php
    $resultado = LeerProducto($nombre, $server, $dbname, $usuario, $password, $productos);
    while($row = $resultado->fetch_assoc())
    {
        $Imagen = $row['Imagen'];
        $NombreProducto = $row['Nombre'];
        $Precio = $row['Precio'];
        $Descripcion = $row['Descripcion'];
        //echo "<img id='Imagen' src='data:image/jpeg;base64," . $row['Imagen'] . "'width=250px height=200px alt='Producto'>";
        //echo "<h1 id='Nombre_Categoria'>".$row["Nombre"]."</h1>";
        //echo "<h2 id='Precio'>".$row["Precio"]."€</h2>";
    }
    ?>
    <div id="Cuerpo">
        <div id="Foto">
            <?php
            echo "<img id='Imagen' src='data:image/jpeg;base64," . $Imagen . "'width=500px height=500px alt='Producto'>";
            ?>
        </div>
        <div id="Info">
            <?php
            echo "<h1 id='Nombre_Categoria'>".$NombreProducto."</h1>";
            echo "<p id='Precio'>".$Precio." €</p>";
            ?>
            <button>Añadir a Comparar</button>
        </div>
    </div>
    <div id="Descripcion">
        <h1>Descripción</h1>
        <?php
            echo "<p id='Descirpcion'>".$Descripcion."</p>"
        ?>
    </div>
</body>
</html>