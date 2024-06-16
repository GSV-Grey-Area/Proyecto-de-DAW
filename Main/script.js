var toggle = false;
var stitchedOutput = "";
var category = "";
var focused;

function SeleccionarCategoria(Nombre)
{
	const parrafo = document.getElementById('Texto');
	parrafo.remove();
	showUser(Nombre);
}

function showUser(str)
{
	if (str == ""){document.getElementById("container").innerHTML = "";	return;}
	
	toggle = true;
	category = str;

	var selector1 = null ? "defaultX" : document.getElementById("X");
	var selector2 = null ? "defaultY" : document.getElementById("Y");
			
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200)
		{
			var split = this.responseText.split("XXXXXXXXX");
			
			document.getElementById("container").innerHTML = "<div style='display: flex; flex-direction: row;'><div style='width: 80%;'>" + split[3] + "</div><div>" + split[0] + "<div><canvas id='myCanvas'></canvas></div>" + split[1] + split[4] + "</div></div>";

			var x = new Function("x", "y", split[2]);
			LoadGraphX(x, window.innerWidth, window.innerHeight);
			top.location = "#dont-go-back-xd"; // xd
			
			var selector1 = document.getElementById("X");
			var selector2 = document.getElementById("Y");
			
			selector1.addEventListener("change", function(){showUser(str);});
			selector2.addEventListener("change", function(){showUser(str);});
			
			var filter = document.getElementById("filter");
			filter.addEventListener
			(
				"click",
				function()
				{
					console.log("Entra");
					if (toggle == true)
					{
						showUser(category);
					}
				}
			);
			
			/*const formed2 = Array.from(document.getElementsByClassName("formed"));
			console.log(formed2);
			formed2.forEach
			(
				function(item)
				{
					var test2;
					item.addEventListener
					(
						"keyup",
						function(item)
						{
							if (test2)
							{
								clearTimeout(test2);
								test2 = 0;
							}
							test2 = setTimeout
							(
								() =>
								{
									//focused = item;
									showUser("portátiles");
								},
								2000
							);
						}
					);
				}
			);*/
			
			//focused.focus();
		}
	}
	
	stitchedOutput = "";
	const formed = Array.from(document.getElementsByClassName("formed"));
	formed.forEach(Pathfinder);
		
	var xmlStringSection = "graph.php?q=" + str + "&innerWidth=" + window.innerWidth + "&innerHeight=" + window.innerHeight;
	if (!(selector1 == null)){xmlStringSection += "&xAxis=" + selector1.value;}
	if (!(selector2 == null)){xmlStringSection += "&yAxis=" + selector2.value;}

	xmlStringSection += stitchedOutput;
	xmlhttp.open("GET", xmlStringSection, true);
	xmlhttp.send();
}

function LoadGraphX(func, innerWidth, innerHeight)
{
	func(innerWidth, innerHeight);
}

window.addEventListener("resize", function(){if (toggle == true){showUser(category);}});
window.addEventListener
(
	"load",
	function()
	{
		var logo = document.getElementById("logo");
		logo.addEventListener("mousedown", function(){logo.src = "./img/logo64c.png";});
	}
);

function Pathfinder(item)
{
	stitchedOutput += "&" + item.name + "=" + item.value;
}