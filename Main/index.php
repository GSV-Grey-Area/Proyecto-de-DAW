<?php
	$_POST['Tipo'] = '';
	require "AccessDB.php";
	$resultado = Read($table);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Data Plotting Tool</title>
		<link rel="icon" type="image/x-icon" href="img/logo16.png">
        <link rel="stylesheet" href="style.css">
		<script src="script.js"></script>
	</head>
	<body>
		<?php include 'header.php';?>
		<div id="Parrafo">
			<p id="Texto">Elija una categoría para comenzar a buscar.</p>
		</div>
		<br>
		<div id="container">
			<?php
                while($row = $resultado->fetch_assoc())
                {
                    echo "<div class='caja' onclick='SeleccionarCategoria(\"" . $row['Nombre'] . "\")'>";
						echo "<img class='image' src='data:image/jpg;base64," . $row['Imagen'] . "' alt='Producto'>";
						echo "<p class='nombreDeLaCategoria'>" . str_replace('_', ' ', $row['Nombre']) . "</p>";
                    echo "</div>";
                }
            ?>
		</div>
		<footer>
			<p>Proyecto de desarrollo de aplicaciones "web"</p>
		</footer>
	</body>
</html>