<?php
//Archivo config .INI
$config = parse_ini_file("config.ini", true);

$server = $config['server'];
$dbname = $config['dbname'];
$table = $config['table'];
$usuario = $config['usuario'];
$password = $config['password'];

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
if(isset($_POST['Categoria'])) {
    $nombre = $_POST["Nombre"];
    $imagen = $_FILES["Imagen"]["name"];
    $imagenCategoria = $_FILES["Imagen_Categoria"]["name"];

    InsertarCategoria($nombre, $imagen, $imagenCategoria, $server, $dbname, $usuario, $password, $table);
} else {
    echo 'No ha llegado Categoria';
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
/*
Funcion que funciona si utilizar AJAX
function InsertarCategoria($nombre,$imagen,$imagenCategoria,$server,$dbname,$usuario,$password,$table)
{
    $conn = new mysqli($server,$usuario,$password,$dbname);
    if($conn->connect_error)
    {
        printf("Conection Error".$conn->connect_error);
        exit();
    }

    $imagen_codificada = base64_encode(file_get_contents("./img/".$imagen));
    $imagen_color_codificada = base64_encode(file_get_contents("./img/".$imagenCategoria));

    $sql = "INSERT INTO $table (Nombre, Imagen, ImagenCategoria) VALUES ('$nombre', '$imagen_codificada', ' $imagen_color_codificada')";
    
    if (mysqli_query($conn, $sql)) {
        console.log("Categoría insertada correctamente.");
    } else {
        echo "Error al insertar la categoría: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);
}

Funcion que utiliza AJAX para insertar datos (No funciona)
function InsertarCategoria($nombre,$imagen,$imagenCategoria,$server,$dbname,$usuario,$password,$table)
{
    $conn = new mysqli($server,$usuario,$password,$dbname);
    if($conn->connect_error)
    {
        printf("Conection Error".$conn->connect_error);
        exit();
    }

    $imagen_codificada = base64_encode(file_get_contents("./img/".$imagen));
    $imagenCategoriaCodificada = base64_encode(file_get_contents("./img/".$imagenCategoria));

    $sql = "INSERT INTO $table (Nombre, Imagen, ImagenCategoria) VALUES ('$nombre', '$imagen_codificada', '$imagenCategoriaCodificada')";
    
    if (mysqli_query($conn, $sql)) {
        console.log("Categoría insertada correctamente.");
    } else {
        echo "Error al insertar la categoría: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);
}

//Comprobacion si llega AJAX
if(isset($_POST['Categoria']))
{
    $datos_recibidos = json_decode($_POST['datos'], true);
    $nombre = $datos_recibidos["Nombre"];
    $imagen = $datos_recibidos["Imagen"];
    $imagenCategoria = $datos_recibidos["Imagen_Categoria"];
    InsertarCategoria($nombre,$imagen,$imagenCategoria,$server,$dbname,$usuario,$password,$table);
}
else
{
    echo 'No ha llegado Categoria';
}
*/
?>