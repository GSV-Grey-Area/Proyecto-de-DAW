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
		<script>		
			function EliminarCategoria(Nombre)
			{
				var resultado = confirm("¿Seguro que quieres eliminar la categoría?");

				if(resultado)
				{
					var form_data = new FormData();
					form_data.append('Tipo', 'Eliminar_Categoria');
					form_data.append('Eliminar_Categoria',Nombre);

					$.ajax({
						url: 'AccessDB.php',
						type: 'POST',
						processData: false,
						contentType: false,
						data: form_data,
						success: function(data){
							location.reload();
						}
	 
					})
					error: function error(xhr, status, error)
					{
						console.error("Error en la solicitud AJAX: ", error);
					}
				}
				else
				{
					return;
				}	
			}

			var inputIDs = [];
			function InsertarProducto() 
			{
				var formData = new FormData();
				var isValid = true; // Variable para verificar si todos los campos están llenos.

				formData.append('Tipo', document.getElementById('Insertar_Producto').value);
				formData.append('nombreTabla', document.getElementById('nombreTablaHidden').value);

				inputIDs.forEach
				(
					function(id)
					{
						var inputElement = document.getElementById(id);
						if (inputElement)
						{ // Comprueba si el elemento existe.
							if (inputElement.value.trim() === '')
							{ // Comprueba si el campo está vacío.
								isValid = false;
								console.warn('El campo con ID ' + id + ' está vacío.');
							}
							formData.append(id, inputElement.value);
						}
						else
						{
							console.warn('El elemento con ID "' + id + '" no existe.');
						}
					}
				);

				if (!isValid)
				{
					alert('Por favor, complete todos los campos obligatorios.');
					return;
				}

				$.ajax({
					url: 'AccessDB.php',
					type: 'POST',
					processData: false,
					contentType: false,
					data: formData,
					success: function(data) {
						location.reload();
					},
					error: function(xhr, status, error) {
						console.error("Error en la solicitud AJAX:", error);
					}
				});
			}

			function EliminarProducto(nombre, producto)
			{
				$.ajax({
                type: "POST",
                url: "AccessDB.php",
                data: {Tipo: "EliminarProducto",categoria: producto, nombre: nombre},
                dataType: "json",
                	success: function(datos) {
						
						location.reload();
					
                	},
					error: function(xhr, status, error) {
    				console.error("Error en la solicitud AJAX: ", error);
					}
            	});
			}

        	$(document).ready(function() {
        		$("#miSelect").change(function()
				{
					var nombreTabla = $("#miSelect option:selected").text();
					var producto = $(this).val();
					LeerProducto(nombreTabla, producto);

					function LeerProducto(producto)
					{
						$.ajax({
							type: "POST",
							url: "AccessDB.php",
							data: { Tipo: "LeerProducto", producto: producto },
							dataType: "json",
						success: function(datos) {
							$('#Error').empty();
							document.getElementById('tablaProductos').innerHTML = "";
							document.getElementById('FormularioProductos').innerHTML = "";

							console.log("Datos recibidos:", datos);
							console.log("Producto:", producto);

							if (datos.mensaje) {
								// Caso: JSON con mensaje de error
								$('#Error').append('<p>' + datos.mensaje + '</p>');

								// Si no hay datos pero tenemos columnas
								if (datos.columnas) {
									// Crear un objeto vacío con las columnas
									var columnasVacias = {};
									datos.columnas.forEach(function(columna) {
									columnasVacias[columna] = "";
								});
									Tabla([columnasVacias], producto); // Llamar a Tabla con columnas vacías para generar inputs
								} else {
									Tabla([], producto); // Llamar a Tabla con un array vacío
								}
							} else {
								// Caso: JSON con datos válidos
								Tabla(datos, producto);
							}
						},
						error: function(xhr, status, error) {
							console.error("Error en la solicitud AJAX:", error);
						}
						});
					}

					function Tabla(datos, nombreTabla)
					{
						var cuerpo = document.getElementById("FormularioProductos");
						cuerpo.innerHTML = "";

						// Crea la tabla y sus elementos si hay datos disponibles:
						var body = document.getElementById("tablaProductos");
						body.innerHTML = "";

						var tabla = document.createElement("table");
						var tblBody = document.createElement("tbody");

						// Crea la cabecera de la tabla:
						var cabecera = document.createElement("thead");
						var filaCabecera = document.createElement("tr");

						var columnas = Object.keys(datos[0]);

						columnas.forEach
						(
							function(columna)
							{
								var th = document.createElement("th");
								th.textContent = columna;
								filaCabecera.appendChild(th);
							}
						);

						var thImagen = document.createElement("th");
						thImagen.textContent = "Borrar";
						filaCabecera.appendChild(thImagen);

						cabecera.appendChild(filaCabecera);
						tabla.appendChild(cabecera);

						datos.forEach
						(
							function(fila)
							{
								var hilera = document.createElement("tr");

								columnas.forEach
								(
									function(columna)
									{
										var celda = document.createElement("td");
										var textoCelda = document.createTextNode(fila[columna]);
										celda.appendChild(textoCelda);
										hilera.appendChild(celda);
									}
								);

								var nombreProducto = fila.Nombre;

								var celdaImagen = document.createElement("td");
								var imagen = document.createElement("img");
								imagen.src = "../Main/img/basura.png";
								imagen.classList.add("icon-small");
								celdaImagen.appendChild(imagen);
								hilera.appendChild(celdaImagen);

								hilera.onclick = (function(nombre, nombreTabla) {
								return function() {
									EliminarProducto(nombre, nombreTabla);
								};
								})(nombreProducto, nombreTabla);

								tblBody.appendChild(hilera);
							}
						);
						
						// Crea los "inputs" basados en los nombres de las columnas del primer elemento de datos.
						if (Array.isArray(datos) && datos.length > 0)
						{
							var row = document.createElement("tr");
							inputIDs = []; // Reiniciar el array
							for (var clave in datos[0])
							{
								var cell = document.createElement("td");
								var input = document.createElement("input");
								input.id = clave;
								input.type = "number";
								inputIDs.push(input.id);
								cell.appendChild(input);
								row.appendChild(cell);
							}

							var celdaImagen = document.createElement("td");
							celdaImagen.innerHTML = "+";
							row.appendChild(celdaImagen);

							var hiddenInput = document.createElement("input");
							hiddenInput.type = "hidden";
							hiddenInput.id = "nombreTablaHidden";
							hiddenInput.value = nombreTabla;
							cuerpo.appendChild(hiddenInput);
							
							tblBody.appendChild(row);

							console.log("IDs de los ''inputs''	: ", inputIDs);
						}
						else
						{
							console.log("No hay datos para crear inputs.");
							return;
						}

						tabla.appendChild(tblBody);
						body.appendChild(tabla);
						tabla.setAttribute("border", "2");
					}
				});
			});
		</script>
	</head>
	<body>
		<header>
            <div style="display: flex; justify-content: space-between; align-content: center;">
                <a href="index.php"><img src="./img/logo64.png" alt="logo" class="logo" id="logo"></a>
				<h1 style="margin: 12px;">Herramienta de trazado de datos</h1>
				<div>
					<a href="information.php"><img src="./img/ISO_7000_-_Ref-No_2760.svg" class="icon"></a>
					<a href="Admin.php"><img src="./img/Herramientas 2.svg" class="icon"></a>
				</div>
			</div>
			<div id="menu">
				<ul>
					<li><a href="index.php">PÁGINA PRINCIPAL</a></li>
					<li ><a href="">INFORMACIÓN</a></li>
					<li><a href="Admin.php">ADMINISTRACIÓN</a></li>
				</ul>
			</div>
        </header>
		
		<div id="cuenta">
			<form action="logout.php" method="POST">
				Sesión iniciada&nbsp;<button type="submit" id="BotonSesion" onclick="CerrarSesion()">Cerrar la sesión de <b><?php echo $_SESSION['usuario'] ?></b></button>
			</form>
		</div>
		
		<form id="FormularioCategoria" >
			<h1>Productos</h1>
			<label for="producto">Seleccione una categoría:</label>
			<select id="miSelect" name="producto" >
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
			<button id="Boton" type="button" onclick="InsertarProducto()">Insertar</button>
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

			<h2>Añadir nueva categoría</h2>
			<div id="Formulario_Categorias">
				<form id="dynamic-form" method="post" action="AccessDB.php" enctype="multipart/form-data" onsubmit="return validateForm()">
					<div class="input-select-pair">
						<input type="hidden" name="Tipo" value="InsertarCategoria">
						<input type="text" name="tableName" id="tableName" placeholder="Ingrese nombre de la tabla">
					</div>
					<div>
					<label for="image1">Imagen de la categoria:</label>
					<input type="file" name="image1" id="image1" accept="image/*">
					</div>
					<div id="input-container"></div>
					<button id="Boton" type="button" onclick="addInput()">Añadir más</button>
					<button id="Boton" type="submit">Enviar</button>
				</form>
			</div>
		</div>
		
		<script>
			function validateForm()
			{
				// Obtiene los campos del formulario:
				const tableName = document.getElementById('tableName').value;
				const image1 = document.getElementById('image1').value;

				var vacios = 0;

				// Verifica si los campos iniciales están vacíos:
				if (!tableName) {vacios++;}
				if (!image1) {vacios++;}

				// Obtiene todos los "inputs" dinámicos y verifica si están vacíos:
				const columnNames = document.querySelectorAll('input[name="columnNames[]"]');
				for (const input of columnNames)
				{
					if (!input.value) {vacios++;}
				}

				if (vacios != 0)
				{
					alert("Formulario de categorías vacío. No se puede insertar.");
					return false;
				}
				else
				{
					return true;
				}
			}

			function EliminarUsuario(ID)
			{
				$.ajax({
                type: "POST",
                url: "AccessDB.php",
                data: {Tipo: "EliminarUsuario", ID: ID},
                dataType: "json",
                	success: function(datos) {
						
						location.reload();
					
                	},
					error: function(xhr, status, error) {
    				console.error("Error en la solicitud AJAX:", error);
					}
            	});
			}

			function InsertarCategoria()
			{
				addInput();
			}

			function addInput()
			{
				const container = document.createElement('div');
				container.className = 'input-select-pair';

				const input = document.createElement('input');
				input.type = 'text';
				input.name = 'columnNames[]';
				input.placeholder = 'Ingrese nombre de la columna';

				const select = document.createElement('select');
				select.name = 'columnTypes[]';

				const options =
				[
					{value: 'FLOAT', text: 'Real'},
					{value: 'VARCHAR(255)', text: 'Texto'},
					{value: 'INT', text: 'Número entero'}
				];

				options.forEach(optionData => {
					const option = document.createElement('option');
					option.value = optionData.value;
					option.textContent = optionData.text;
					select.appendChild(option);
				});

				container.appendChild(input);
				container.appendChild(select);

				document.getElementById('input-container').appendChild(container);
			}
		</script>
    </body>
</html>