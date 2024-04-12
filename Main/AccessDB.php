<?php
/*
$config = parse_ini_file("config.ini", true);
//No funciona utilzando el archivo .ini

$server = $config['server'];
$dbname = $config['dbname'];
$table = $config['table'];
$usuario = $config['usuario'];
$password = $config['password'];
*/

$server = "localhost:3306";
$dbname = "prueba_proyecto";
$table = "categoria";
$usuario = "root";
$password = "";

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
?>