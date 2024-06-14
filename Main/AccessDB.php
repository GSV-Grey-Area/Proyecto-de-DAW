<?php
	ini_set('display_errors', 1);
	$config = parse_ini_file("config.ini", true);

	$server = $config['server'];
	$dbname = $config['dbname'];
	$table = $config['table'];
	$usuario = $config['usuario'];
	$password = $config['password'];
	$productos = $config['productos'];
	
	$conn;	
	// Lee las categorias para la página principal:
	function Read($table)
	{
		$conn = Connection();
		$sql = "SELECT * FROM $table";
		$resultado = mysqli_query($conn, $sql);

		if (!$resultado)
		{
			printf("Error en la consulta: %s\n", mysqli_error($conn));
			exit();
		}
		
		mysqli_close($conn);
		return $resultado;
	}
	if(isset($_POST['Tipo']))
	{
		$tipo = $_POST['Tipo'];
		switch($tipo)
		{
			case 'InsertarCategoria':
				$columnNames = $_POST['columnNames'];
    			$columnTypes = $_POST['columnTypes'];
				$tableName = $_POST['tableName'];

				if
				(
					empty($columnNames) ||
					empty($columnTypes) ||
					count($columnNames) !== count($columnTypes)
				)
				{
					echo 'Datos del formulario no válidos.';
				}

				$uploadedImage1 = $_FILES['image1']['tmp_name'];

				if ($uploadedImage1)
				{
					InsertarCategoria($tableName, $columnNames, $columnTypes);
					InsertarEnCategorias($tableName, $uploadedImage1);
				}
				else
				{
					die('Error al subir las imágenes.');
				}
				
				break;
				
			case 'Buscar_Categoria':
				$nombre = $_POST['Nombre_Buscar_Categoria'];
				$resultado = BuscarCategoria($nombre, $table);
				foreach($resultado as $row)
				{
					echo "<div id='Caja' onclick='SeleccionarCategoria(" . $row['ID'] . ")'>";
					echo "<img id='Imagen' src='data:image/jpeg;base64," . $row['Imagen'] . "' width=250px height=200px alt='Producto'>";
					echo "<p id='Nombre_Categoria'>" . $row['Nombre'] . "</p>";
					echo "</div>";
				}
				break;
				
			case 'Eliminar_Categoria':
				$nombre = $_POST['Eliminar_Categoria'];
				EliminarCategoria($nombre, $table);
				break;

			case 'Insertar_Producto':
				$data = [];
				$nombreTabla = $_POST['nombreTabla'];
				echo $nombreTabla;
					
				// Elimina 'nombreTabla' del array $data, si existe
				if (isset($_POST['nombreTabla']))
				{
					unset($_POST['nombreTabla']);
				}
					
				foreach ($_POST as $key => $value)
				{
					if ($key != 'Tipo') // Excluye el campo Tipo del array de datos
					{
						$data[$key] = $value; // Recoge los datos sin limpiar aquí
					}
				}
				
				InsertarProducto($nombreTabla, $data);
				break;
				
			case 'LeerProducto':
				$NombreCategoria = $_POST['producto'];
				LeerProducto($NombreCategoria);
				break;
				
			case 'EliminarProducto':
				$Categoria = $_POST['categoria'];
				$NombreProducto = $_POST['nombre'];
				EliminarProducto($Categoria,$NombreProducto);
				break;
				
			case 'registro':
				$user = $_POST['usuario'];
				$contrasena = $_POST['contrasena'];
				RegistrarUsuario($user, $contrasena);
				break;
				
			case 'iniciarsesion':
				$user = $_POST['usuario'];
				$contrasena = $_POST['contrasena'];
				IniciarSesion($user, $contrasena);
				break;
				
			case 'EliminarUsuario':
				$id = $_POST['ID'];
				EliminarUsuario($id);
				break;
				
			default:
				break;
		}
	}

	function EliminarUsuario($id)
	{
		$conn = Connection();

		$sql = "DELETE FROM usuarios WHERE ID = '$id'";

		if (mysqli_query($conn, $sql)) {
			echo json_encode(['mensaje' =>  "OK"]);
		} else {
			echo "Error al eliminar el registro: " . mysqli_error($conn);
			return "False";
		}
	
		mysqli_close($conn);
	}

	function IniciarSesion($user, $contrasena)
	{
		session_start();

    	$conn = Connection();

    	// Escapa el nombre de usuario para evitar inyección SQL
    	$user = mysqli_real_escape_string($conn, $user);

    	// Consulta SQL para obtener los datos del usuario
    	$sql = "SELECT * FROM usuarios WHERE Nombre = '$user'";

		$result = mysqli_query($conn, $sql);

		if($result == " ")
		{
			echo json_encode(['mensaje' =>  "Error"]);
		}
		else
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$nombre = $row['Nombre'];
				$contrasenaBD = $row['Contrasena'];
			}

			if($nombre == "admin" && $contrasena == $contrasenaBD)
			{
				$_SESSION['usuario'] = 'admin';
				echo json_encode(['mensaje' =>  "admin"]);
			}
			else
			{
				echo json_encode(['mensaje' =>  "Error"]);
			}
		}
		mysqli_free_result($result);
		mysqli_close($conn);

	}

	function RegistrarUsuario($user, $contrasena)
	{
		$conn = Connection();

		$sql = "INSERT INTO usuarios (Nombre, Contrasena) VALUES ('$user', '$contrasena')";		
	
		if ($conn->query($sql) === TRUE) {
			echo json_encode(["mensaje" => "Registro exitoso"]);
		} else {
			echo json_encode(["mensaje" => "Error"]);
		}		
		
		$conn->close();
	}

	function InsertarEnCategorias($tableName, $image1Path)
	{
		$conn = Connection();

		// Escapa los valores para evitar inyecciones SQL
		$tableName = $conn->real_escape_string($tableName);

		// Verifica si existen los archivos
		if (!file_exists($image1Path))
		{
			echo "Error: Imagen no existe.";
			return;
		}

		// Codifica las imágenes a base64
		
		$type = pathinfo($image1Path, PATHINFO_EXTENSION);
		
		
		$imagen_codificada = /*'data:image/' . $type . ';base4,' .*/ base64_encode(file_get_contents($image1Path));

		// Construye la consulta SQL para insertar en la tabla categorias
		$sql = "INSERT INTO categoria (Nombre, Imagen) VALUES ('$tableName', '$imagen_codificada')";

		// Ejecuta la consulta
		//if ($conn->query($sql) === TRUE)
		if (mysqli_query($conn, $sql))
		{
			echo "Entrada en categorias creada exitosamente";
			//header("Location: Admin.php");
			exit();
		}
		else
		{
			echo "Error al insertar en categorias: " . $conn->error;
			//header("Location: Admin.php");
			exit();
		}

		$conn->close();
	}

	function EliminarProducto($Categoria,$NombreProducto)
	{
		$conn = Connection();
		$sql = "DELETE FROM $Categoria WHERE Nombre = '$NombreProducto'";

		if (mysqli_query($conn, $sql)) {
			echo json_encode(['mensaje' =>  "OK"]);
		} else {
			echo json_encode(['mensaje' => "Error"]);
		}
	
		mysqli_close($conn);
		
	}

	function LeerProducto($NombreCategoria) {
		$conn = Connection();
		$NombreCategoria = mysqli_real_escape_string($conn, $NombreCategoria); // Evitar inyección SQL
		$sql = "SELECT * FROM $NombreCategoria";
		
		$resultado = mysqli_query($conn, $sql);
		
		if (!$resultado)
		{
			$error = array("error" => "Error en la consulta: " . mysqli_error($conn));
			mysqli_close($conn);
			echo json_encode($error);
			exit;
		}
	
		$productos = [];
		while ($row = $resultado->fetch_assoc()) {
			$productos[] = $row;
		}
	
		if (empty($productos)) {
			// Obtiene los nombres de las columnas
			$columnas = [];
			$sqlColumnas = "SHOW COLUMNS FROM $NombreCategoria";
			$resultadoColumnas = mysqli_query($conn, $sqlColumnas);
	
			if ($resultadoColumnas) {
				while ($row = $resultadoColumnas->fetch_assoc()) {
					$columnas[] = $row['Field'];
				}
			}
	
			mysqli_close($conn);
	
			if (!empty($columnas)) {
				echo json_encode(["mensaje" => "No hay datos", "columnas" => $columnas]);
			} else {
				echo json_encode(["mensaje" => "No hay datos y no se pudieron obtener los nombres de las columnas"]);
			}
		} else {
			mysqli_close($conn);
			echo json_encode($productos);
		}
	}

	function InsertarCategoria($tableName,$columnNames, $columnTypes) {
		$conn = Connection();
	
		// Añadir la columna ID autoincremental
		$sql = "CREATE TABLE `$tableName` (
			`ID` INT NOT NULL AUTO_INCREMENT,
			PRIMARY KEY (`ID`),
			`Nombre` VARCHAR(255) NOT NULL,";
	
		$columns = [];
		for ($i = 0; $i < count($columnNames); $i++) {
			// Proteger los nombres de las columnas y los tipos de datos
			$columnName = $conn->real_escape_string($columnNames[$i]);
			$columnType = $conn->real_escape_string($columnTypes[$i]);
			$columns[] = "`$columnName` $columnType";
		}
		$sql .= implode(", ", $columns) . ")";
	
		// Ejecuta la consulta
		if ($conn->query($sql) === TRUE) {
			echo "Tabla creada exitosamente";
			header("Location: Admin.php");
		} else {
			echo "Error al crear la tabla: " . $conn->error;
			header("Location: Admin.php");
		}
	
		$conn->close();
	}

	function BuscarCategoria($nombre, $table)
	{
		$conn = Connection();
		$sql = "SELECT * FROM $table WHERE Nombre = '$nombre'";
		$result = $conn->query($sql);

		if($result === false)
		{
			return array('error' => "Error al ejecutar la consulta: " . $conn->error);
		}

		$categorias = array();

		if($result->num_rows > 0)
		{
			// Si se encontraron resultados, los almacena en una matriz:
			while($row = $result->fetch_assoc())
			{
				$categorias[] = $row;
			}
		}
		else
		{
			return array('error' => "No se encontró ninguna categoría con ese nombre.");
		}

		$conn->close();
		return $categorias;
	}

	function EliminarCategoria($nombre, $table)
	{
		$conn = Connection();

		if ($nombre !== null) {
			// Si se proporciona un nombre, elimina la fila específica
			$sqlDeleteRow = "DELETE FROM $table WHERE Nombre = '$nombre'";
			if ($conn->query($sqlDeleteRow) === FALSE) {
				$conn->close();
				return array('error' => "Error al eliminar la fila de la categoría: " . $conn->error);
			}
		}

		// Elimina la tabla completa
		$sqlDropTable = "DROP TABLE $nombre";
		if ($conn->query($sqlDropTable) === TRUE)
		{
			$conn->close();
			if ($nombre !== null) {
				return array('success' => "Se eliminó la fila de la categoría y la tabla correctamente.");
			} else {
				return array('success' => "Se eliminó la categoría y la tabla correctamente.");
			}
		}
		else
		{
			$conn->close();
			if ($nombre !== null) {
				return array('error' => "Error al eliminar la fila de la categoría y la tabla: " . $conn->error);
			} else {
				return array('error' => "Error al eliminar la categoría y la tabla: " . $conn->error);
			}
		}
	}

	function LeerProductoPorCategoria($categoria, $productos)
	{
		$conn = Connection();
		$sql = "SELECT * FROM $productos WHERE Categoria = '$categoria'";
		$resultado = mysqli_query($conn, $sql);

		if (!$resultado)
		{
			printf("Error en la consulta: %s\n", mysqli_error($conn));
			exit();
		}
		
		mysqli_close($conn);
		return $resultado;
	}
	
	function InsertarProducto($nombreTabla, $data)
	{
		$conn = Connection();

		if (empty($data))
		{
			echo json_encode(["mensaje" => "No hay datos para insertar"]);
			return;
		}

		$columns = implode(", ", array_keys($data));
		$placeholders = implode(", ", array_fill(0, count($data), '?'));
		$valuesWithQuotes = array_map(function($value) {
			return "'" . $value . "'";
		}, $data);
		
		$values = implode(", ", $valuesWithQuotes);

		echo $columns;
		echo $values;

		$sql = "INSERT INTO $nombreTabla ($columns) VALUES ($placeholders)";
		$stmt = $conn->prepare($sql);

		if ($stmt)
		{
			// Enlaza los valores y ejecuta la consulta
			$types = str_repeat('s', count($data)); // Utiliza count($data) en lugar de count($values)
			$stmt->bind_param($types, ...array_values($data)); // Los valores se pasan como parámetros individuales
			
			if ($stmt->execute()) {
				echo json_encode(["mensaje" => "OK"]);
			} else {
				echo json_encode(["mensaje" => "Error al guardar los datos", "error" => $stmt->error]);
			}

			// Cierra la declaración preparada
			$stmt->close();
		} else {
			// Si la preparación de la consulta falla
			echo json_encode(["mensaje" => "Error al preparar la consulta", "error" => $conn->error]);
		}

		$conn->close();
	}

	function Connection()
	{
		global $server, $usuario, $password, $dbname;
		$conn = new mysqli($server, $usuario, $password, $dbname);

		if ($conn->connect_error)
		{
			return array('error' => "Error de conexión: " . $conn->connect_error);
		}
		
		return $conn;
	}
	
	function Load($tableX)
	{
		$conn = Connection();
		$sql = "SELECT * FROM $productos WHERE Categoria = '$categoria'";
		$resultado = mysqli_query($conn, $sql);

		if (!$resultado)
		{
			printf("Error en la consulta: %s\n", mysqli_error($conn));
			exit();
		}
		
		mysqli_close($conn);
		return $resultado;
	}

	function Prueba()
	{
		$image = $_POST['pic'];
		//Stores the filename as it was on the client computer.
		$imagename = $_FILES['pic']['name'];
		//Stores the filetype e.g image/jpeg
		$imagetype = $_FILES['pic']['type'];
		//Stores any error codes from the upload.
		$imageerror = $_FILES['pic']['error'];
		//Stores the tempname as it is given by the host when uploaded.
		$imagetemp = $_FILES['pic']['tmp_name'];

		//The path you wish to upload the image to
		$imagePath = "images/";

		if(is_uploaded_file($imagetemp))
		{
			if(move_uploaded_file($imagetemp, $imagePath . $imagename))
			{
				echo "Sussecfully uploaded your image.";
			}
			else
			{
				echo "Failed to move your image.";
			}
		}
		else
		{
			echo "Failed to upload your image.";
		}
	}

	function ReadUsuarios()
	{
		$conn = Connection();

		$sql = "SELECT * FROM usuarios";

		$resultado = mysqli_query($conn, $sql);

		if (!$resultado)
		{
			printf("Error en la consulta: %s\n", mysqli_error($conn));
			exit();
		}
		
		mysqli_close($conn);
		return $resultado;
	}
?>