function EliminarCategoria(Nombre)
{
	var resultado = confirm("¿Seguro que quieres eliminar la categoría?");

	if(resultado)
	{
		var form_data = new FormData();
		form_data.append('Tipo', 'Eliminar_Categoria');
		form_data.append('Eliminar_Categoria', Nombre);

		$.ajax({
			url: 'AccessDB.php',
			type: 'POST',
			processData: false,
			contentType: false,
			data: form_data,
			success: function(data){location.reload();}
		});
		
		error: function error(xhr, status, error){console.error("Error en la solicitud AJAX: ", error);}
	}

	return;
}

var inputIDs = [];

function InsertarProducto() {
	var formData = new FormData();
	var isValid = true; // Variable para verificar si todos los campos están llenos.

	

	inputIDs.forEach(function(id, index) {
	var inputElement = document.getElementById(id);
	if (inputElement) { // Comprueba si el elemento existe.
		if (index > 0 && inputElement.value.trim() === '') { // Comprueba si el campo está vacío, omitiendo el primer input.
				isValid = false;
			   console.warn('El campo con ID ' + id + ' está vacío.');
		}
		formData.append(id, inputElement.value);
	} else {
		console.warn('El elemento con ID "' + id + '" no existe.');
	}
	});

	if (!isValid) {
		alert('Por favor, complete todos los campos obligatorios.');
		return;
	}

	formData.append('Tipo', document.getElementById('Insertar_Producto').value);
	formData.append('nombreTabla', document.getElementById('nombreTablaHidden').value);

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
	var resultado = confirm("¿Seguro que quieres eliminar el producto?");
	
	if(resultado)
	{
		$.ajax
		({
			type: "POST",
			url: "AccessDB.php",
			data: {Tipo: "EliminarProducto",categoria: producto, nombre: nombre},
			dataType: "json",
			success: function(datos)
			{
				if(datos.mensaje == "OK"){location.reload();}
				else{alert("Algo ha salido mal");}
			},
			error: function(xhr, status, error) {console.error("Error en la solicitud AJAX: ", error);}
		});
	}

	return;
}

$(document).ready
(
	function()
	{
		$("#miSelect").change(function()
		{
			var nombreTabla = $("#miSelect option:selected").text();
			var producto = $(this).val();
			LeerProducto(nombreTabla, producto);

			function LeerProducto(producto)
			{
				$.ajax
				({
					type: "POST",
					url: "AccessDB.php",
					data: {Tipo: "LeerProducto", producto: producto},
					dataType: "json",
					success: function(datos)
					{
						$('#Error').empty();
						document.getElementById('tablaProductos').innerHTML = "";
						document.getElementById('FormularioProductos').innerHTML = "";

						if (datos.mensaje)
						{
							// Caso: JSON con mensaje de error
							$('#Error').append('<p>' + datos.mensaje + '</p>');

							// Si no hay datos pero hay columnas, crea un objeto vacío con ellas.
							if (datos.columnas)
							{
								var columnasVacias = {};
								datos.columnas.forEach(function(columna) {columnasVacias[columna] = "";});
								Tabla([columnasVacias], producto); // Llama a Tabla con columnas vacías para generar inputs
							}
							else
							{
								Tabla([], producto); // Llama a Tabla con una matriz vacía
							}
						}
						else
						{
							// Caso: JSON con datos válidos
							Tabla(datos, producto);
						}
					},
					error: function(xhr, status, error) {console.error("Error en la solicitud AJAX:", error);}
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

						celdaImagen.onclick = (function(nombre, nombreTabla) {
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
					inputIDs = [];
					var isFirstInput = true; // Variable para verificar el primer input

					for (var clave in datos[0])
					{
						var cell = document.createElement("td");
						var input = document.createElement("input");
						input.id = clave;
						input.type = "text";

						if (isFirstInput)
						{
							input.readOnly = true;
							isFirstInput = false;
						}

						inputIDs.push(input.id);
						cell.appendChild(input);
						row.appendChild(cell);
					}

					var celdaImagen = document.createElement("td");
					celdaImagen.innerHTML = "+";
					celdaImagen.style.cursor = "pointer";
					celdaImagen.addEventListener("click", function(){InsertarProducto();});
					row.appendChild(celdaImagen);

					var hiddenInput = document.createElement("input");
					hiddenInput.type = "hidden";
					hiddenInput.id = "nombreTablaHidden";
					hiddenInput.value = nombreTabla;
					cuerpo.appendChild(hiddenInput);
					
					tblBody.appendChild(row);
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
	}
);

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
			
function deleteLastInput() {
	const container = document.getElementById('input-container');
	if (container.lastChild) {
		container.removeChild(container.lastChild);
	} else {
		alert('No hay más entradas para eliminar.');
	}
}