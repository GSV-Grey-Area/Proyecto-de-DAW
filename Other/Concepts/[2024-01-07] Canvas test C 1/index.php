<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<canvas id="myCanvas" style="border: 1px solid grey"></canvas>
		<script>
			<?php 
				$canvasX = 1000;
				$canvasY = 1000;
				
				echo 'var canvasX = ' . $canvasX . ';';
				echo 'var canvasY = ' . $canvasY . ';';
			?>
			
			document.getElementById("myCanvas").height = canvasX;
			document.getElementById("myCanvas").width = canvasY;

			const canvas = document.getElementById("myCanvas");
			const ctx = canvas.getContext("2d");

			ctx.beginPath();
			ctx.fillStyle = "#FF0000";
			
			<?php
				$x = GetCSV('test', 0);
				$y = GetCSV('test', 1);
				$elementSize = 50;
				
				function GetCSV($target, $column)
				{
					require_once('GetCSV.php');
					$test = MainFunction($target . '.csv', $column);
					return $test;
				}
				
				$arrlength = count($x);
				
				for($i = 1; $i < $arrlength; $i++)
				{
					echo 'ctx.fillRect(' . $x[$i]*100 . ', ' . $canvasY - $y[$i] - $elementSize + 1 . ', ' . $elementSize . ', ' . $elementSize . ');';
					echo 'base_image = new Image();';
					echo 'base_image.src = '';
					echo '"';
					if ($i < 11)
					{
						echo '0';
					}
					echo $i - 1 . '.png";';
					
					echo 'ctx.drawImage(base_image, ' . $x[$i]*100 . ', ' . $canvasY - $y[$i] - $elementSize + 1 . ', ' . $elementSize . ', ' . $elementSize . ');';
				}
			?>
			
			ctx.stroke();
		</script>
	</body>
</html>