<?php
    if (isset($_SESSION['usuario'])) {
        header("Location: Admin.php");
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
    <title>Inicio de sesión</title>
    <script>
        function IniciarSesion(usuario, contrasena)
        {
            var usuario = document.getElementById('usuario').value;
            var contrasena = document.getElementById('contrasena').value;

            var errores = document.querySelectorAll('.error');
            errores.forEach(function(error) {
            error.classList.add('hidden');
            });
            
            if( usuario == "" &&  contrasena == "")
            {
                document.getElementById('usuarioError').classList.remove('hidden');
                document.getElementById('usuarioError').classList.add('error');
                document.getElementById('contrasenaError').classList.remove('hidden');
                document.getElementById('contrasenaError').classList.add('error');
                return;
            }
            else if(usuario == "")
            {
                document.getElementById('usuarioError').classList.remove('hidden');
                document.getElementById('usuarioError').classList.add('error');
                return;
            }
            else if(contrasena == "")
            {
                document.getElementById('contrasenaError').classList.remove('hidden');
                document.getElementById('contrasenaError').classList.add('error');
                return;
            }
            else
            {
                $.ajax({
                url: 'AccessDB.php',
                type: 'POST',
                data: {
                    Tipo: "iniciarsesion",
                    usuario: usuario,
                    contrasena: contrasena
                },
                dataType: "json",
                success: function(response) {
                    if(response.mensaje === 'admin')
                    {
                        window.location.href = 'Admin.php';
                    }
                    else if(response.mensaje === 'Error')
                    {
                        document.getElementById('loginError').classList.remove('hidden');
                        document.getElementById('loginError').classList.add('error');
                    }
                },
                error: function() {
                    document.getElementById('loginError').classList.remove('hidden');
                    document.getElementById('loginError').classList.add('error');
                }
            });
            }
        }
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
		<div id="Menu">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="Admin.php">Admin</a></li>
			</ul>
		</div>
    </header>

    <div id="Caja_InicioSesion">
        <h1>Iniciar Sesión</h1>

        <label for="usuario">Usuario</label>
        <input type="text" name="usuario" id="usuario">

        <p class="hidden" id="usuarioError">El usuario no puede estar vacio</p>

        <label for="contrasena">Contraseña</label>
        <input type="password" name="contrasena" id="contrasena">

        <p class="hidden" id="contrasenaError">La contraseña no puede estar vacia</p>
        <p class="hidden" id="loginError">Usuario o contraseña incorrecto</p>

        <button type="button" onclick="IniciarSesion()">Iniciar Sesión</button>
    </div>
    
</body>
</html>
