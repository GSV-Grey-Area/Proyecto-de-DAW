<?php
	$_POST['Tipo'] = '';
	require "AccessDB.php";
	
	// Recoge el ID de la categoría seleccionada.
	if(isset($_GET['ID'])) // Cambiado 'ID' a 'id'
	{
		$categoriaID = $_GET['ID']; // Cambiado 'ID' a 'id'
	}
	else
	{
		echo "No ha llegado el ID";
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/logo16.png">
    <link rel="stylesheet" href="CSSindex.css">
    <title>Document</title>
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
            $resultado = LeerProductoPorCategoria($categoriaID, $productos);
            while($row = $resultado->fetch_assoc())
            {
                echo "<div id='Caja' onclick='SeleccionarProducto(\"".$row['Nombre']."\")'>";
                echo "<img id='Imagen' src='data:image/png;base64," . $row['Imagen'] . "'width=250px height=200px alt='Producto'>";
                echo "<p id='Nombre_Categoria'>".$row["Nombre"]."</p>";
                echo "<h2 id='Precio'>".$row["Precio"]."€</h2>";
                echo "</div>";
            }
        ?>
    </div>
</body>
</html>