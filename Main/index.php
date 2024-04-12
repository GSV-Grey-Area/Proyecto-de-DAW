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
                    <li><a href="">Ayuda</a></li>
                    <li><a href="">Info</a></li>
                    <li><a href="">Cuenta</a></li>
                    <li><a href="">Cesta</a></li>
                </ul>
            </div>
            <hr>
        </header>
        <div id="Categorias">
            <?php
                while($row = $resultado->fetch_assoc())
                {
                    echo "<div id='Caja'>";
                    echo "<img id='Imagen' src='data:image/jpeg;base64," . base64_encode($row['Imagen']) . "'width=300px height=200px alt='Producto'>";
                    echo "<p id='Nombre_Categoria'>".$row["Nombre"]."</p>";
                    echo "</div>";
                }
            ?>
        </div>
	</body>
</html>