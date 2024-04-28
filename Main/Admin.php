<?php
require "AccessDB.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSSindex.css">
    <link rel="stylesheet" href="CSSAdministrador.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Administrador</title>
    <script>
        function AgregarCategoria()
        {
            var form_data = new FormData();
            form_data.append('Tipo', document.getElementById("Categoria").value);
            form_data.append('Nombre', document.getElementById("Nombre").value);
            form_data.append('Imagen', document.getElementById("Imagen").files[0]);
            form_data.append('Imagen_Categoria', document.getElementById("Imagen_Categoria").files[0]);

            var datos = {
                Tipo: Categoria,
                Nombre: Nombre,
                Imagen: Imagen,
                Imagen_Categoria: Imagen_Categoria
            };
            
            $.ajax({
            url: 'AccessDB.php',
            type: 'POST',
            processData: false,
            contentType: false,
            data: form_data,
            success: function (data) {
            console.log(data);
            var Bien = document.getElementById("Bien").hidden = false;
            },
            error: function (error) {
                console.error("Error en la solicitud AJAX:", error);
            }
            });

        }

        function BuscarCategoria()
        {
            var form_data = new FormData();
            form_data.append('Tipo', document.getElementById('Buscar_Categoria').value);
            form_data.append('Nombre_Categoria', document.getElementById('Nombre_Buscar_Categoria').value);
            console.log(form_data);
            
            $.ajax({
            url: 'AccessDB.php',
            type: 'POST',
            processData: false,
            contentType: false,
            data: form_data,
            success: function (data) {
                
            }
            });
            error: function error(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
            }
        }

        function EliminarCategoria() {

            var form_data = new FormData();
            form_data.append('Tipo', document.getElementById('Eliminar_Categoria').value);
            form_data.append('Eliminar_Categoria', document.getElementById('Eliminar_Nombre_Categoria').value);

            $.ajax({
                url: 'AccessDB.php',
                type: 'POST',
                processData: false,
                contentType: false,
                data: form_data,
                success: function(data){

                }
 
            })
            error: function error(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
            }
            
        }
    </script>
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
    <form id="FormularioCategoria">
        <input type="hidden" id="Categoria" name="Categoria" value="Categoria">
        <h1>Añadir Categoria</h1>
        <label for="Nombre">Nombre Categoria</label>
        <input type="text" id="Nombre" name="Nombre" required>
        <br>
        <label for="Imagen">Imagen Categoria</label>
        <input type="file" id="Imagen" name="Imagen" required>
        <br>
        <label for="Imagen_Categoria">Imagen Texto Categoria</label>
        <input type="file" id="Imagen_Categoria" name="Imagen_Categoria" required>
        <br>
        <button onclick="AgregarCategoria()">Añadir Categoria</button>
    </form>
        <p id="Bien" hidden>Se ha añadido la categoria correctamente</p>
        <p id="Mal" hidden>Ha ocurrido un error al añadir la categoria</p>

    <form id="FormularioCategoria" action="AccessDB.php" method="POST">
        <input type="hidden" id="Buscar_Categoria" name="Tipo" value="Buscar_Categoria">
        <h1>Buscar Categoria</h1>
        <label for="Nombre">Nombre Categoria</label>
        <input type="text" id="Nombre_Buscar_Categoria" name="Nombre_Buscar_Categoria" required>
        <button onclick="BuscarCategoria()">Buscar Categoria</button>
    </form>

    <form id="FormularioCategoria">
        <input type="hidden" id="Eliminar_Categoria" name="Tipo" value="Eliminar_Categoria">
        <h1>Eliminar Categoria</h1>
        <input type="text" name="Eliminar_Nombre_Categoria" id="Eliminar_Nombre_Categoria">
        <button type="submit" onclick="EliminarCategoria()">Eliminar Categoria</button>
    </form>
    </body>
</html>