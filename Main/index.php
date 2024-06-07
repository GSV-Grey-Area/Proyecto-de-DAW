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
        <script>
			var toggle = false;
			
			function SeleccionarCategoria(Nombre)
			{
				const parrafo = document.getElementById('Texto');
				parrafo.remove();
				//location.href ="Categoria.php?ID=" + Nombre;
				//showUser("portátiles");
				showUser(Nombre);
				document.getElementById("Categorias").style.display = "none";
			}
			
			function showUser(str)
			{
				if (str == "")
				{
					document.getElementById("container").innerHTML = "";
					return;
				}
				
				toggle = true;

				var selector1 = null ? "defaultX" : document.getElementById("X");
				var selector2 = null ? "defaultY" : document.getElementById("Y");
						
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function()
				{
					if (this.readyState == 4 && this.status == 200)
					{
						var split = this.responseText.split("XXXXXXXXX");
						
						document.getElementById("container").innerHTML = "<div style='display: flex; flex-direction: row;'><div style='width: 80%;'>" + split[3] + "</div><div>" + split[0] + "<div><canvas id='myCanvas'></canvas></div>" + split[1] + "</div></div>";

						var x = new Function("x", "y", split[2]);
						LoadGraphX(x, window.innerWidth, window.innerHeight);
						top.location = "#dont-go-back-xd"; // xd
						
						var selector1 = document.getElementById("X");
						var selector2 = document.getElementById("Y");
						
						selector1.addEventListener("change", function(){showUser("portátiles");});
						selector2.addEventListener("change", function(){showUser("portátiles");});
					}
				}
				
				var xmlStringSection = "graph.php?q=" + str + "&innerWidth=" + window.innerWidth + "&innerHeight=" + window.innerHeight;
				if (!(selector1 == null)){xmlStringSection += "&xAxis=" + selector1.value;}
				if (!(selector2 == null)){xmlStringSection += "&yAxis=" + selector2.value;}
				xmlhttp.open("GET", xmlStringSection, true);
				xmlhttp.send();
			}
			
			function LoadGraphX(func, innerWidth, innerHeight)
			{
				func(innerWidth, innerHeight);
			}
			
			window.addEventListener("resize", function(){if (toggle == true){showUser("portátiles");}});
			window.addEventListener
			(
				"load",
				function()
				{
					var logo = document.getElementById("logo");
					logo.addEventListener("mousedown", function(){logo.src = "./img/logo64c.png";});
				}
			);
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
		<div id="Parrafo">
			<p id="Texto">Elija una categoría para comenzar a buscar.</p>
		</div>

		<div id="container">
			<?php
                while($row = $resultado->fetch_assoc())
                {
                    echo "<div class='caja' onclick='SeleccionarCategoria(\"" . $row['Nombre'] . "\")'>";
						echo "<img class='image' src='data:image/jpg;base64," . $row['Imagen'] . "' alt='Producto'>";
						echo "<p class='nombreDeLaCategoria'>" . $row["Nombre"] . "</p>";
                    echo "</div>";
                }
            ?>
		</div>
	</body>
</html>