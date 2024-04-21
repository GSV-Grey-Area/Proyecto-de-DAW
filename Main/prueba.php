<!DOCTYPE html>
	<head>
		<style>
			<?php
				$title = "Portátiles y parecidos";
				require_once 'svgMaker.php';
				require_once 'headerTitleLoader.php';
			?>
			
			body
			{
				background-color: #F0F0F0;
				overflow-Y: hidden;
			}
			
			.header
			{
				background-color: #FFFFFF;
				padding: 5px;
				border-radius: 10px;
				box-shadow: 0px 0px 2px 0px #000000;
				margin-
			}
			
			
			.left
			{
				flex: 20%;
				margin: 10px 5px 10px 0px;
			}
			
			.right
			{
				flex: 70%;
				margin: 10px 0px 10px 5px;
			}
			
			
			.block
			{
				background-color: #FFFFFF;
				border-radius: 10px;
				box-sizing: border-box;
				height: 90vh;
				padding: 5px;
								box-shadow: 0px 0px 2px 0px #000000;
			}
			
			@media (max-width: 500px)
			{
				.left, .right
				{
					flex: 100%;
					height: 90vw;
				}
			}
		</style>
	</head>
	<body onresize="myFunction()" onload="myFunction()">
		<div class="header" style="display: flex; gap: 10px;">
			<img src="img/logo128.png" style="width: 10%; max-width: 125px; border-radius: 10px;">
			<?php
				require_once 'headerTitleImageLoader.php';
			?>
		</div>
		<div style="display: flex; flex-wrap: wrap; flex-direction: row; justify-content: space-between; box-sizing: border-box;">
			<div id="left" class="block left">1</div>
			<div id="right" class="block right">
				<canvas id="myCanvas" style="border: 1px solid grey"></canvas>
				<script>
					<?php
						require_once 'Xindex.php';
						echo 'console.log("Required");';
					?>
				</script>
			</div>
		</div>
		<script>
			function myFunction()
			{
				let w = window.innerWidth;
				let h = window.innerHeight;

				let vh = w/100;

				console.log(w);

				if (w/10 < 125)
				{
					document.getElementById("cf").style.height = w/10 + "px";
					
					if (w > 501 && w < 1250)
					{
						//document.getElementById("left").style.height = (90 + (9*w/150)/vh) + "vh";
						document.getElementById("left").style.height = (h - w/10 - 50) + "px";
						document.getElementById("right").style.height = (h - w/10 - 50) + "px";
						console.log("Small");
					}
					else
					{
						document.getElementById("left").style.height = "90vw";
						document.getElementById("right").style.height = "90vw";
						console.log("Very small");
					}
				}
				else
				{
					document.getElementById("cf").style.height = "125px";
					//document.getElementById("left").style.height = "90vh";
					
				}
				
				if (w > 501 && w < 1250)
				{
					//document.getElementById("left").style.height = (90 + (9*w/150)/vh) + "vh";
					document.getElementById("left").style.height = (h - w/10 - 40) + "px";
					document.getElementById("right").style.height = (h - w/10 - 40) + "px";
					console.log("Big");
				}
				else if (w >= 1250)
				{
					document.getElementById("left").style.height = (h - 160) + "px";
					document.getElementById("right").style.height = (h - 160) + "px";
					console.log("Very big");
				}		
			}
		</script>
	</body>
</html>
