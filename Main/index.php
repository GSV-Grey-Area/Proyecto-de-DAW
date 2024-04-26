<?php
require "AccessDB.php";

$resultado = Read($server,$dbname,$usuario,$password,$table);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Aquí empieza el proyecto...</title>
		<link rel="icon" type="image/x-icon" href="img/logo16.png">
        <link rel="stylesheet" href="CSSindex.css">
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
        <div id="Categorias">
            <?php
                while($row = $resultado->fetch_assoc())
                {
                    echo "<div id='Caja' onclick='SeleccionarCategoria(".$row['ID'].")'>";
                    echo "<img id='Imagen' src='data:image/jpeg;base64," . $row['Imagen'] . "'width=250px height=200px alt='Producto'>";
                    echo "<p id='Nombre_Categoria'>".$row["Nombre"]."</p>";
                    echo "</div>";
                }
            ?>
        </div>
	</body>
</html>
<script src="./JavaScript.js"></script>