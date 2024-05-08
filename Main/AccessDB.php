<?php
ini_set('display_errors', 1);
//Archivo config .INI
$config = parse_ini_file("config.ini", true);

$server = $config['server'];
$dbname = $config['dbname'];
$table = $config['table'];
$usuario = $config['usuario'];
$password = $config['password'];
$productos = $config['productos'];

//Funcion para leer las categorias para la pagina HOME
function Read($server,$dbname,$usuario,$password,$table)
{
    $conn = new mysqli($server,$usuario,$password,$dbname);
    if($conn->connect_error)
    {
        printf("Conection Error".$conn->connect_error);
        exit();
    }

    $sql = "SELECT * FROM $table";
    $resultado = mysqli_query($conn,$sql);

    if (!$resultado) {
        printf("Error en la consulta: %s\n", mysqli_error($conn));
        exit();
    }
    
    mysqli_close($conn);
    return $resultado;
}

//Comprobacion si llega AJAX
/*
if(isset($_POST['Categoria'])) {

    $nombre = $_POST["Nombre"];
    $imagen = $_FILES["Imagen"]["name"];
    $imagenCategoria = $_FILES["Imagen_Categoria"]["name"];

    InsertarCategoria($nombre, $imagen, $imagenCategoria, $server, $dbname, $usuario, $password, $table);
}
*/

if(isset($_POST['Tipo'])) {
    $tipo = $_POST['Tipo'];
    switch($tipo) {
        case 'Categoria':

            $nombre = $_POST["Nombre"];
            $imagen = $_FILES["Imagen"]["name"];
            $imagenCategoria = $_FILES["Imagen_Categoria"]["name"];
            InsertarCategoria($nombre, $imagen, $imagenCategoria, $server, $dbname, $usuario, $password, $table);
            break;
        case 'Buscar_Categoria':
            $nombre = $_POST['Nombre_Buscar_Categoria'];
            $resultado = BuscarCategoria($nombre,$server, $dbname, $usuario, $password, $table);
            foreach($resultado as $row) {
                echo "<div id='Caja' onclick='SeleccionarCategoria(" . $row['ID'] . ")'>";
                echo "<img id='Imagen' src='data:image/jpeg;base64," . $row['Imagen'] . "' width=250px height=200px alt='Producto'>";
                echo "<p id='Nombre_Categoria'>" . $row['Nombre'] . "</p>";
                echo "</div>";
            }
            break;
        case 'Eliminar_Categoria':
            $nombre = $_POST['Eliminar_Categoria'];
            EliminarCategoria($nombre,$server, $dbname, $usuario, $password, $table);
            break;

        case 'Insertar_Producto':
            $categoria_producto = $_POST['Nombre_Categoria_Producto'];
            $nombre_producto = $_POST['Nombre_Producto'];
            $imagen_producto = $_FILES['Imagen_Producto']["name"];
            $precio_producto = $_POST['Precio_Producto'];
            $descripcion_producto = $_POST['Descripcion_Producto'];
            InsertarProducto($categoria_producto, $nombre_producto, $imagen_producto, $precio_producto, $descripcion_producto, $server, $dbname, $usuario, $password, $productos);
            break;
        case 'LeerProducto':
            $nombredelproducto = $_POST['Nombre'];
            break;
        default:
            break;
    }
}
else
{
    echo "No llega el Tipo";
}

//Funcion para insertar Categorias
function InsertarCategoria($nombre, $imagen, $imagenCategoria, $server, $dbname, $usuario, $password, $table) {
    $conn = new mysqli($server, $usuario, $password, $dbname);
    if($conn->connect_error) {
        printf("Conection Error".$conn->connect_error);
        exit();
    }

    $imagen_codificada = base64_encode(file_get_contents("./img/".$imagen));
    $imagenCategoriaCodificada = base64_encode(file_get_contents("./img/".$imagenCategoria));

    $sql = "INSERT INTO $table (Nombre, Imagen, ImagenCategoria) VALUES ('$nombre', '$imagen_codificada', '$imagenCategoriaCodificada')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Categoría insertada correctamente.";
    } else {
        echo "Error al insertar la categoría: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);
}

function BuscarCategoria($nombre, $server, $dbname, $usuario, $password, $table) {
    $conn = new mysqli($server, $usuario, $password, $dbname);

    if($conn->connect_error) {
        return array('error' => "Error de conexión: ".$conn->connect_error);
    }

    $sql = "SELECT * FROM $table WHERE Nombre = '$nombre'";
    $result = $conn->query($sql);

    if($result === false) {
        return array('error' => "Error al ejecutar la consulta: " . $conn->error);
    }

    $categorias = array();

    if($result->num_rows > 0) {
        // Si se encontraron resultados, almacenarlos en un array
        while($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
    } else {
        // No se encontró ninguna categoría con ese nombre
        return array('error' => "No se encontró ninguna categoría con ese nombre.");
    }

    $conn->close();

    // Devolver el array de categorías
    return $categorias;
}

function EliminarCategoria($nombre, $server, $dbname, $usuario, $password, $table)
{
    $conn = new mysqli($server, $usuario, $password, $dbname);

    if ($conn->connect_error) {
        return array('error' => "Error de conexión: " . $conn->connect_error);
    }

    $sql = "DELETE FROM $table WHERE Nombre = '$nombre'";

    if ($conn->query($sql) === TRUE) {
        // La eliminación se realizó con éxito
        $conn->close();
        return array('success' => "Se eliminó la categoría correctamente.");
    } else {
        // Error al ejecutar la consulta SQL
        $conn->close();
        return array('error' => "Error al eliminar la categoría: " . $conn->error);
    }
}

//Funcion para leer los productos por su Categoria
function LeerProductoPorCategoria($categoria,$server, $dbname, $usuario, $password, $productos)
{
    $conn = new mysqli($server, $usuario, $password, $dbname);

    if ($conn->connect_error) {
        return array('error' => "Error de conexión: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM $productos WHERE Categoria = '$categoria'";

    $resultado = mysqli_query($conn,$sql);

    if (!$resultado) {
        printf("Error en la consulta: %s\n", mysqli_error($conn));
        exit();
    }
    
    mysqli_close($conn);
    return $resultado;
}

function InsertarProducto($categoria_producto, $nombre_producto, $imagen_producto, $precio_producto, $descripcion_producto, $server, $dbname, $usuario, $password, $productos)
{
    $conn = new mysqli($server, $usuario, $password, $dbname);

    if ($conn->connect_error) {
        return array('error' => "Error de conexión: " . $conn->connect_error);
    }

    $imagen_codificada = base64_encode(file_get_contents("./img/".$imagen_producto));

    $sql = "INSERT INTO $productos (Categoria, Nombre, Imagen, Precio, Descripcion) VALUES ('$categoria_producto', '$nombre_producto', '$imagen_codificada', '$precio_producto', '$descripcion_producto')";

    if (mysqli_query($conn, $sql)) {
        echo "Categoría insertada correctamente.";
    } else {
        echo "Error al insertar la categoría: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);
}

function LeerProducto($nombre, $server, $dbname, $usuario, $password, $productos)
{
    $conn = new mysqli($server, $usuario, $password, $dbname);

    if ($conn->connect_error) {
        return array('error' => "Error de conexión: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM $productos WHERE Nombre = '$nombre'";

    $resultado = mysqli_query($conn,$sql);

    if (!$resultado) {
        printf("Error en la consulta: %s\n", mysqli_error($conn));
        exit();
    }
    
    mysqli_close($conn);
    return $resultado;
}
?>