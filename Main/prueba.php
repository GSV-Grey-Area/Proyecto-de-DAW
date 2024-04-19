<!DOCTYPE html>
	<head>
		<style>
			<?php
				$title = "Portátiles";
				require_once 'headerTitleLoader.php';
			?>
			
			body
			{
				background-color: #EEEEEE;
			}
			
			.header
			{
				background-color: #FFFFFF;
				padding: 5px;
				border-radius: 10px;
				box-shadow: 0px 0px 2px 0px #000000;
			}
		</style>
	</head>
	<body onresize="myFunction()" onload="myFunction()">
		<div class="header">
		<?php
			require_once 'headerTitleImageLoader.php';
		?>
		</div>

		<script>
			function myFunction()
			{
				let w = window.innerWidth;
				let h = window.innerHeight;

				if (w/10 < 125)
				{
					document.getElementById("cf").style.height = w/10 + "px";
				}
				else
				{
					document.getElementById("cf").style.height = "125px";
				}
			}
		</script>
	</body>
</html>