<?php
	require "AccessDB.php";
	session_start();
	if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin')
	{
		header("Location: InicioSesion.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style.css">
		<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
		<title>Administración</title>
		<link rel="icon" type="image/x-icon" href="img/logo16.png">
		<script src="admin.js"></script>
	</head>
	<body>
		<?php include 'header.php';?>
		
		<div id="cuenta">
			<form action="logout.php" method="POST">
				Sesión iniciada&nbsp;<button type="submit" id="BotonSesion" onclick="CerrarSesion()">Cerrar la sesión de <b><?php echo $_SESSION['usuario'] ?></b></button>
			</form>
		</div>
		
		<form id="FormularioCategoria" >
			<h1>Productos</h1>
			<label for="producto">Seleccione una categoría:</label>
			<select id="miSelect" name="producto">
				<?php
					$result = Read($table);
					while ($row = $result->fetch_assoc())
					{
						echo "<option value='".$row['Nombre']."'>".$row['Nombre']."</option>";
					}
				?>
    		</select>
			<div id="Error"></div>
			<div id="tablaProductos"></div>
			<div id="FormularioProductos"></div>
			<input type="hidden" id="Insertar_Producto" name="Tipo" value="Insertar_Producto">
		</form>	

		<div id="FormularioCategoria">
			<h1>Categorías</h1>
			<table>
				<tr>
					<th>ID</th>
					<th>Nombre</th>
					<th>Imagen</th>
					<th style="border-right: none; border-top: none;">Borrar</th>
				</tr>

				<?php			
					$result = Read($table);
					while($row = $result->fetch_assoc())
					{
						echo "<tr>";
							echo "<td>".$row['ID']."</td>";
							echo "<td>".$row['Nombre']."</td>";
							echo "<td><img src='data:image/jpg;base64," . $row['Imagen'] . "'width=50px height=50px alt='Producto'></td>";
							echo "<td id='".$row['Nombre']."' onclick=\"EliminarCategoria('".$row['Nombre']."')\"><img src='./img/basura.png' width=50px height=50px alt='Basura'></td>";
						echo "</tr>";
					}
				?>
			</table>

			<h2>Nueva categoría</h2>
			<div id="Formulario_Categorias">
				<form id="dynamic-form" method="post" action="AccessDB.php" enctype="multipart/form-data" onsubmit="return validateForm()">
					<div class="input-select-pair">
						<input type="hidden" name="Tipo" value="InsertarCategoria">
						<input type="text" name="tableName" id="tableName" placeholder="Nombre de la tabla...">
					</div>
					<div>
						<input type="text" name="nombre" id="nombre" readonly value="Nombre">
					</div>
					<div>
						<label for="image1">Imagen de la categoria:</label>
						<input type="file" name="image1" id="image1" accept="image/*">
					</div>
					
					<div id="input-container"></div>
					<button id="Boton" type="button" onclick="AddInput()">Añadir columna</button>
					<button id="Boton" type="submit">Crear</button>
				</form>
			</div>
		</div>
		
		<footer>
			<p>Proyecto de desarrollo de aplicaciones "web"</p>
		</footer>
    </body>
</html>